<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsultationController extends Controller
{
    /**
     * Affiche la liste des consultations.
     */
    public function index(Request $request)
    {
        // Récupère les consultations avec les infos patient pour éviter le problème N+1
        $consultations = Consultation::with('patient');
        
        // Filtre pour les consultations du jour
        if ($request->has('today') && $request->today) {
            $consultations = $consultations->whereDate('created_at', today());
        }
        
        $consultations = $consultations->latest()->get();
        return view('consultations.index', compact('consultations'));
    }

    /**
     * Affiche le formulaire de création pour un patient donné.
     */
    public function create(Patient $patient)
    {
        return view('consultations.create', compact('patient'));
    }

    /**
     * Enregistre une nouvelle consultation.
     */
    public function store(Request $request, $patientId) // Change ici pour recevoir l'ID brut
{
    // 1. Récupère le patient manuellement pour être certain qu'il existe
    $patient = Patient::findOrFail($patientId);

    // 2. Validation
    $validated = $this->validateConsultation($request);

    // 3. Force l'ID du patient
    $validated['patient_id'] = $patient->id;

    // 4. Création
    Consultation::create($validated);

    // 5. Redirection
    return redirect()->route('patients.show', $patient->id)
        ->with('success', 'Consultation enregistrée.');
}
    /**
     * Génère un PDF d'ordonnance.
     */
    public function generatePDF($id)
    {
        $consultation = Consultation::with('patient')->findOrFail($id);

        $pdf = Pdf::loadView('consultations.pdf', [
            'consultation' => $consultation
        ]);

        return $pdf->download('Ordonnance_' . $consultation->patient->nom . '.pdf');
    }

    /**
     * Méthode privée pour centraliser les règles de validation.
     */
    private function validateConsultation(Request $request)
    {
        return $request->validate([
            'date_consultation' => 'required|date|before_or_equal:today',
            'symptomes'         => 'nullable|string|max:1000',
            'diagnostic'        => 'required|string|min:10|max:2000',
            'traitement'        => 'required|string|min:10|max:2000',
            'poids'             => 'nullable|numeric|min:0.5|max:300',
            'tension'           => 'nullable|string|regex:/^[0-9]{1,3}\/[0-9]{1,3}$/',
        ], [
            'date_consultation.required' => 'La date est requise.',
            'diagnostic.required'        => 'Un diagnostic est obligatoire.',
            'diagnostic.min'             => 'Le diagnostic doit être plus détaillé (min 10 caractères).',
            'traitement.required'        => 'Le traitement est obligatoire.',
            'traitement.min'             => 'Le traitement doit être plus détaillé (min 10 caractères).',
            'tension.regex'              => 'Format tension invalide (ex: 12/8).',
        ]);
    }
}
