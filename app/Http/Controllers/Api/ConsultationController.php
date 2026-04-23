<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Display consultations for a specific patient
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

        $consultations = $patient->consultations()->orderBy('date_consultation', 'desc')->get();
        return response()->json($consultations);
    }

    /**
     * Store a new consultation
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'date_consultation' => 'required|date',
            'symptomes' => 'required|string',
            'diagnostic' => 'required|string',
            'traitement' => 'required|string',
            'poids' => 'nullable|numeric',
            'tension' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        // Seuls médecin et admin peuvent créer des consultations
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

        $consultation = new Consultation($request->all());
        $consultation->patient_id = $patient->id;
        $consultation->save();

        return response()->json($consultation, 201);
    }

    /**
     * Display a specific consultation
     */
    public function show(Consultation $consultation)
    {
        $user = Auth::user();
        $patient = $consultation->patient;
        
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

        return response()->json($consultation);
    }

    /**
     * Update a consultation
     */
    public function update(Request $request, Consultation $consultation)
    {
        $request->validate([
            'date_consultation' => 'sometimes|date',
            'symptomes' => 'sometimes|string',
            'diagnostic' => 'sometimes|string',
            'traitement' => 'sometimes|string',
            'poids' => 'nullable|numeric',
            'tension' => 'nullable|string'
        ]);

        $user = Auth::user();
        $patient = $consultation->patient;
        
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

        $consultation->update($request->all());
        return response()->json($consultation);
    }

    /**
     * Delete a consultation
     */
    public function destroy(Consultation $consultation)
    {
        $user = Auth::user();
        
        // Seul l'admin peut supprimer
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $consultation->delete();
        return response()->json(['message' => 'Consultation supprimée']);
    }

    /**
     * Mes consultations (pour le patient connecté)
     */
    public function myConsultations()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('patient')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $patient = Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['message' => 'Dossier non trouvé'], 404);
        }

        $consultations = $patient->consultations()->orderBy('date_consultation', 'desc')->get();
        return response()->json($consultations);
    }
}
