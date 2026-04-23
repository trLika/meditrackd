<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdonnanceController extends Controller
{
    /**
     * Display ordonnances for a specific patient
     */
    public function index(Patient $patient)
    {
        $user = Auth::user();
        
        // Vérifier les permissions
        if ($user->hasRole('patient')) {
            if ($patient->user_id !== $user->id) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }
        elseif ($user->hasRole('medecin')) {
            $userServices = $user->services->pluck('id');
            if (!$userServices->contains($patient->service_id)) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }

        $ordonnances = $patient->ordonnances()->orderBy('created_at', 'desc')->get();
        return response()->json($ordonnances);
    }

    /**
     * Store a new ordonnance
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'medicaments' => 'required|string',
            'posologie' => 'required|string',
            'duree' => 'required|string',
            'instructions' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        // Seuls médecin et admin peuvent créer des ordonnances
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

        $ordonnance = new Ordonnance($request->all());
        $ordonnance->patient_id = $patient->id;
        $ordonnance->save();

        return response()->json($ordonnance, 201);
    }

    /**
     * Display a specific ordonnance
     */
    public function show(Ordonnance $ordonnance)
    {
        $user = Auth::user();
        $patient = $ordonnance->patient;
        
        // Vérifier les permissions
        if ($user->hasRole('patient')) {
            if ($patient->user_id !== $user->id) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }
        elseif ($user->hasRole('medecin')) {
            $userServices = $user->services->pluck('id');
            if (!$userServices->contains($patient->service_id)) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
        }

        return response()->json($ordonnance);
    }

    /**
     * Update an ordonnance
     */
    public function update(Request $request, Ordonnance $ordonnance)
    {
        $request->validate([
            'medicaments' => 'sometimes|string',
            'posologie' => 'sometimes|string',
            'duree' => 'sometimes|string',
            'instructions' => 'nullable|string'
        ]);

        $user = Auth::user();
        $patient = $ordonnance->patient;
        
        // Seuls médecin et admin peuvent modifier
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

        $ordonnance->update($request->all());
        return response()->json($ordonnance);
    }

    /**
     * Delete an ordonnance
     */
    public function destroy(Ordonnance $ordonnance)
    {
        $user = Auth::user();
        
        // Seul l'admin peut supprimer
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $ordonnance->delete();
        return response()->json(['message' => 'Ordonnance supprimée']);
    }

    /**
     * Mes ordonnances (pour le patient connecté)
     */
    public function myOrdonnances()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['message' => 'Dossier non trouvé'], 404);
        }

        $ordonnances = $patient->ordonnances()->orderBy('created_at', 'desc')->get();
        return response()->json($ordonnances);
    }
}
