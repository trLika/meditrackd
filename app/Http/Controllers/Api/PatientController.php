<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Si l'utilisateur est un médecin, ne montrer que les patients de son service
        if ($user->hasRole('medecin')) {
            $patients = Patient::whereHas('service', function($query) use ($user) {
                $query->whereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
            })->with('service', 'consultations')->get();
        }
        // Si admin, montrer tous les patients
        elseif ($user->hasRole('administrateur')) {
            $patients = Patient::with('service', 'consultations')->get();
        }
        else {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string|max:10',
            'antecedents' => 'nullable|string',
            'allergies' => 'nullable|string',
            'service_id' => 'required|exists:services,id',
            'is_critique' => 'boolean'
        ]);

        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->hasRole(['administrateur', 'medecin'])) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Si médecin, vérifier que le patient est dans son service
        if ($user->hasRole('medecin')) {
            $userServices = $user->services->pluck('id');
            if (!$userServices->contains($request->service_id)) {
                return response()->json(['message' => 'Vous ne pouvez créer des patients que dans votre service'], 403);
            }
        }

        $patient = Patient::create($request->all());

        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if ($user->hasRole('patient')) {
            // Le patient ne peut voir que son propre dossier
            if ($patient->user_id !== $user->id) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }
        elseif ($user->hasRole('medecin')) {
            // Le médecin ne peut voir que les patients de son service
            $userServices = $user->services->pluck('id');
            if (!$userServices->contains($patient->service_id)) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }
        // Admin peut voir tous les patients

        $patient->load('service', 'consultations', 'ordonnances');
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'sexe' => 'sometimes|in:M,F',
            'date_naissance' => 'sometimes|date',
            'telephone' => 'sometimes|string|max:20',
            'adresse' => 'nullable|string',
            'groupe_sanguin' => 'nullable|string|max:10',
            'antecedents' => 'nullable|string',
            'allergies' => 'nullable|string',
            'service_id' => 'sometimes|exists:services,id',
            'is_critique' => 'boolean'
        ]);

        $user = Auth::user();
        
        // Vérifier les permissions
        if (!$user->hasRole(['administrateur', 'medecin'])) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Si médecin, vérifier que le patient est dans son service
        if ($user->hasRole('medecin')) {
            $userServices = $user->services->pluck('id');
            if (!$userServices->contains($patient->service_id)) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }

        $patient->update($request->all());

        return response()->json($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $user = Auth::user();
        
        // Seul l'admin peut supprimer des patients
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $patient->delete();

        return response()->json(['message' => 'Patient supprimé']);
    }

    /**
     * Mes patients (pour le médecin connecté)
     */
    public function myPatients()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('medecin')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $patients = Patient::whereHas('service', function($query) use ($user) {
            $query->whereHas('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            });
        })->with('consultations')->get();

        return response()->json($patients);
    }

    /**
     * Patients du service (pour le médecin connecté)
     */
    public function servicePatients()
    {
        return $this->myPatients(); // Même logique
    }

    /**
     * Mon dossier (pour le patient connecté)
     */
    public function myDossier()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $patient = Patient::where('user_id', $user->id)
            ->with('service', 'consultations', 'ordonnances')
            ->first();

        if (!$patient) {
            return response()->json(['message' => 'Dossier non trouvé'], 404);
        }

        return response()->json($patient);
    }
}
