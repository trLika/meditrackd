<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Service; // Import nécessaire
use App\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Les stagiaires ne peuvent que voir (index, show)
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';
            $isMedecin = $user->hasRole('medecin');
            
            if (!$isAdmin && !$isMedecin && $user->hasRole('stagiaire')) {
                if (in_array($request->route()->getActionMethod(), ['create', 'store', 'edit', 'update', 'destroy'])) {
                    abort(403, 'Les stagiaires ne sont pas autorisés à effectuer cette action.');
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Patient::with('service');
        $user = Auth::user();
        
        // Si c'est l'admin, pas de filtre
        if ($user->hasRole('admin') || $user->name === 'Administrateur') {
            // Admin voit tous les patients - aucun filtre
        } else {
            // Médecin - filtre par ses services, sauf s'il fait une recherche précise
            $userServices = $user->services()->pluck('services.id');
            
            if (!$request->filled('search')) {
                if ($userServices->isNotEmpty()) {
                    $query->whereIn('service_id', $userServices);
                } else {
                    $query->whereRaw('1 = 0'); // Force empty result
                }
            }
            // En cas de recherche, on laisse le médecin chercher partout pour qu'il puisse demander l'accès
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $cleanSearch = str_replace(' ', '', $search);
            
            $query->where(function($q) use ($search, $cleanSearch) {
                // Recherche spécifique par ID (ex: #2 ou id:2)
                if (preg_match('/^(id:|#)\s*(\d+)$/i', trim($search), $matches)) {
                    $q->where('id', $matches[2]);
                    return; // On arrête là pour ne chercher QUE l'ID
                }

                // Toujours chercher par ID exact si c'est un nombre valide
                if (is_numeric($search)) {
                    $q->orWhere('id', $search);
                }
                
                // Recherche par téléphone exact (si la recherche nettoyée contient 8 chiffres)
                if (is_numeric($cleanSearch) && strlen($cleanSearch) === 8) {
                    $q->orWhere('telephone', $cleanSearch);
                } else {
                    // Recherche par termes textuels
                    $q->orWhere(function($textSearch) use ($search) {
                        $terms = explode(' ', $search);
                        foreach ($terms as $term) {
                            if (empty($term)) continue;
                            $textSearch->where(function($sub) use ($term) {
                                $sub->where('nom', 'like', '%' . $term . '%')
                                    ->orWhere('prenom', 'like', '%' . $term . '%')
                                    ->orWhere('telephone', 'like', '%' . $term . '%')
                                    ->orWhereHas('service', function($s) use ($term) {
                                        $s->where('name', 'like', '%' . $term . '%');
                                    });
                            });
                        }
                    });
                }
            });
        }

        // Filtre pour les patients critiques
        if ($request->filled('critique') && $request->critique) {
            $query->where('is_critique', true);
        }

        // Log la requête et les résultats
        $patients = $query->orderBy('id', 'asc')->paginate(10);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        // Limiter les services selon le rôle de l'utilisateur
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur') {
            $isAdmin = true;
        }
        
        // Admin voit tous les services, médecin voit seulement ses services
        if ($user->name === 'Administrateur' || $isAdmin) {
            $services = Service::all();
        } else {
            $services = $user->services;
        }
            
        return view('patients.create', compact('services'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur') {
            $isAdmin = true;
        }
        
        // Pour les médecins, valider que le service appartient à leurs services
        if (!$isAdmin) {
            $userServices = $user->services()->pluck('services.id');
            $request->merge([
                'service_id' => $userServices->contains($request->service_id) ? $request->service_id : $userServices->first()
            ]);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|regex:/^[24-9][0-9]{7}$/',
            'adresse' => 'nullable|string|max:255',
            'groupe_sanguin' => 'nullable|string|max:10',
            'antecedents' => 'nullable|string',
            'service_id' => 'required|exists:services,id', // Validation du service
            'is_critique' => 'boolean'
        ]);

        $validated['is_critique'] = $request->has('is_critique');
        
        $patient = Patient::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Création patient',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
        ]);

        return redirect()->route('patients.index')->with('success', 'Patient ajouté avec succès !');
    }

    public function edit($id)
    {
        $patient = Patient::with('service')->findOrFail($id);
        
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';
        
        // Vérifier l'accès
        if (!$isAdmin) {
            $userServices = $user->services()->pluck('services.id');
            $hasServiceAccess = $userServices->contains($patient->service_id);
            
            if (!$hasServiceAccess) {
                $hasCrossAccess = \App\Models\PatientAccess::where('user_id', $user->id)
                    ->where('patient_id', $patient->id)
                    ->where(function($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    })
                    ->exists();
                
                if (!$hasCrossAccess) {
                    abort(403, 'Accès non autorisé à ce patient.');
                }
            }
        }
        
        // Admin voit tous les services, médecin voit ses propres services
        if ($isAdmin) {
            $services = Service::all();
        } else {
            $services = $user->services;
            // Toujours inclure le service actuel pour l'affichage
            if (!$services->contains('id', $patient->service_id)) {
                $services->push($patient->service);
            }
        }
        return view('patients.edit', compact('patient', 'services'));
    }

    public function update(Request $request, Patient $patient)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';
        
        // Vérifier l'accès
        if (!$isAdmin) {
            $userServices = $user->services()->pluck('services.id');
            $hasServiceAccess = $userServices->contains($patient->service_id);
            
            if (!$hasServiceAccess) {
                $hasCrossAccess = \App\Models\PatientAccess::where('user_id', $user->id)
                    ->where('patient_id', $patient->id)
                    ->where(function($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    })
                    ->exists();
                
                if (!$hasCrossAccess) {
                    abort(403, 'Accès non autorisé à ce patient.');
                }
            }
        }
        
        // Pour les médecins, limiter les services à leurs propres services + le service actuel
        if (!$isAdmin) {
            $userServicesIds = $user->services()->pluck('services.id');
            if (!$userServicesIds->contains($request->service_id) && $request->service_id != $patient->service_id) {
                $request->merge(['service_id' => $patient->service_id]);
            }
        }
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|regex:/^[24-9][0-9]{7}$/',
            'adresse' => 'nullable|string|max:255',
            'groupe_sanguin' => 'nullable|string|max:10',
            'antecedents' => 'nullable|string',
            'service_id' => 'required|exists:services,id',
            'is_critique' => 'boolean'
        ]);

        $validated['is_critique'] = $request->has('is_critique');
        $patient->update($validated);

        // Si le patient a été transféré dans un service du médecin, rendre l'accès inter-service permanent (ou le supprimer car inutile)
        if (!$isAdmin) {
            $userServicesIds = $user->services()->pluck('services.id');
            if ($userServicesIds->contains($patient->service_id)) {
                \App\Models\PatientAccess::where('user_id', $user->id)
                    ->where('patient_id', $patient->id)
                    ->update(['expires_at' => null]); // Devient permanent/validé
            }
        }

        $oldServiceId = $patient->getOriginal('service_id');
        $newServiceId = $patient->service_id;

        if ($oldServiceId != $newServiceId) {
            $oldServiceName = \App\Models\Service::find($oldServiceId)->name ?? 'Inconnu';
            $newServiceName = \App\Models\Service::find($newServiceId)->name ?? 'Inconnu';
            $details = "Transfert de service : $oldServiceName -> $newServiceName";
            $action = 'Transfert patient';
        } else {
            $details = null;
            $action = 'Mise à jour dossier';
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
            'details' => $details
        ]);

        return redirect()->route('patients.index')->with('success', 'Dossier mis à jour.');
    }

    public function show($id)
    {
        $patient = Patient::with('service')->findOrFail($id);
        
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';
        
        // Vérifier si le médecin a accès à ce patient (admin a toujours accès)
        if (!$isAdmin) {
            $userServices = $user->services()->pluck('services.id');
            $hasServiceAccess = $userServices->contains($patient->service_id);
            
            if (!$hasServiceAccess) {
                // Vérifier s'il y a un accès inter-service valide
                $hasCrossAccess = \App\Models\PatientAccess::where('user_id', $user->id)
                    ->where('patient_id', $patient->id)
                    ->where(function($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    })
                    ->exists();
                
                if (!$hasCrossAccess) {
                    return redirect()->route('patients.request-access', $patient->id)
                        ->with('warning', 'Vous n\'avez pas accès à ce dossier. Vous pouvez faire une demande d\'accès inter-service.');
                }
            }
        }
        
        $consultations = $patient->consultations()->orderBy('date_consultation', 'desc')->get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Consultation du dossier',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
        ]);

        return view('patients.show', compact('patient', 'consultations'));
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin') || $user->name === 'Administrateur';
        
        // Vérifier l'accès
        if (!$isAdmin) {
            $userServices = $user->services()->pluck('services.id');
            if (!$userServices->contains($patient->service_id)) {
                $hasCrossAccess = \App\Models\PatientAccess::where('user_id', $user->id)
                    ->where('patient_id', $patient->id)
                    ->where(function($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    })
                    ->exists();
                
                if (!$hasCrossAccess) {
                    abort(403, 'Accès non autorisé à ce patient.');
                }
            }
        }
        
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient supprimé.');
    }
}
