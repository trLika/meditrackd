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
    }

    public function index(Request $request)
    {
        $query = Patient::query();
        $user = Auth::user();
        
        // Log simple pour debug
        \Log::info('Patient Index:', [
            'user_name' => $user->name,
            'user_role' => $user->getRoleNames()->first()
        ]);

        // Si c'est l'admin, pas de filtre
        if ($user->hasRole('admin') || $user->name === 'Administrateur') {
            // Admin voit tous les patients - aucun filtre
            \Log::info('Admin access - showing all patients');
        } else {
            // Médecin - filtre par ses services
            $userServices = $user->services()->pluck('services.id');
            \Log::info('Doctor access - filtering by services:', ['service_ids' => $userServices->toArray()]);
            
            if ($userServices->isNotEmpty()) {
                $query->whereIn('service_id', $userServices);
            } else {
                // Si le médecin n'a pas de services, retourner une liste vide
                $query->whereRaw('1 = 0'); // Force empty result
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('prenom', 'like', '%' . $search . '%')
                  ->orWhere('telephone', 'like', '%' . $search . '%');
            });
        }

        // Filtre pour les patients critiques
        if ($request->filled('critique') && $request->critique) {
            $query->where('is_critique', true);
        }

        // Log la requête et les résultats
        $patients = $query->orderBy('nom', 'asc')->paginate(10);
        \Log::info('Patient Query Results:', [
            'total_count' => $patients->total(),
            'current_page' => $patients->currentPage(),
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        // Limiter les services selon le rôle de l'utilisateur
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur' && $user->hasRole('admin')) {
            $isAdmin = true;
        }
        
        $services = $isAdmin ? 
            Service::all() : 
            $user->services;
            
        return view('patients.create', compact('services'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur' && $user->hasRole('admin')) {
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
            'telephone' => 'required|string|regex:/^[67][0-9]{7}$/',
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
        $patient = Patient::findOrFail($id);
        
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur' && $user->hasRole('admin')) {
            $isAdmin = true;
        }
        
        // Vérifier si le médecin a accès à ce patient
        if (!$isAdmin && !$user->services()->pluck('services.id')->contains($patient->service_id)) {
            abort(403, 'Accès non autorisé à ce patient.');
        }
        
        $services = $isAdmin ? Service::all() : $user->services;
        return view('patients.edit', compact('patient', 'services'));
    }

    public function update(Request $request, Patient $patient)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur' && $user->hasRole('admin')) {
            $isAdmin = true;
        }
        
        // Vérifier si le médecin a accès à ce patient
        if (!$isAdmin && !$user->services()->pluck('services.id')->contains($patient->service_id)) {
            abort(403, 'Accès non autorisé à ce patient.');
        }
        
        // Pour les médecins, limiter les services à leurs propres services
        if (!$isAdmin) {
            $request->merge([
                'service_id' => $user->services()->pluck('services.id')->contains($request->service_id) ? $request->service_id : $patient->service_id
            ]);
        }
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|regex:/^[67][0-9]{7}$/',
            'adresse' => 'nullable|string|max:255',
            'groupe_sanguin' => 'nullable|string|max:10',
            'antecedents' => 'nullable|string',
            'service_id' => 'required|exists:services,id',
            'is_critique' => 'boolean'
        ]);

        $validated['is_critique'] = $request->has('is_critique');
        $patient->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mise à jour dossier',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
        ]);

        return redirect()->route('patients.index')->with('success', 'Dossier mis à jour.');
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur' && $user->hasRole('admin')) {
            $isAdmin = true;
        }
        
        // Vérifier si le médecin a accès à ce patient
        if (!$isAdmin && !$user->services()->pluck('services.id')->contains($patient->service_id)) {
            abort(403, 'Accès non autorisé à ce patient.');
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
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin SEULEMENT si c'est vraiment l'admin par nom
        if ($user->name === 'Administrateur' && $user->hasRole('admin')) {
            $isAdmin = true;
        }
        
        // Vérifier si le médecin a accès à ce patient
        if (!$isAdmin && !$user->services()->pluck('services.id')->contains($patient->service_id)) {
            abort(403, 'Accès non autorisé à ce patient.');
        }
        
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient supprimé.');
    }
}
