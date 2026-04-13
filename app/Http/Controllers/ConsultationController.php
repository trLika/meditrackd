<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class ConsultationController extends Controller
{

public function index()
{
    // On récupère toutes les consultations avec les infos du patient lié
    $consultations = \App\Models\Consultation::with('patient')->get();

    // On les envoie à la vue 'consultations.index'
    return view('consultations.index', compact('consultations'));
}

    public function create(Patient $patient)
{
    // Débogage : Afficher les informations du patient reçu
    \Log::info('Patient pour consultation:', ['id' => $patient->id, 'nom' => $patient->nom, 'prenom' => $patient->prenom]);

    return view('consultations.create', compact('patient'));
}
public function store(Request $request, Patient $patient)
{
    // Débogage : Afficher les données reçues
    \Log::info('Données consultation reçues:', $request->all());
    \Log::info('Patient reçu:', ['id' => $patient->id, 'nom' => $patient->nom]);
    
    // Validation des données avec types stricts et messages personnalisés
    try {
        $validated = $request->validate([
            'date_consultation' => 'required|date|before_or_equal:today|after:1900-01-01',
            'symptomes' => 'nullable|string|max:1000',
            'diagnostic' => 'required|string|min:10|max:2000',
            'traitement' => 'required|string|min:10|max:2000',
            'poids' => 'nullable|numeric|min:0.5|max:300',
            'tension' => 'nullable|string|regex:/^[0-9]{1,3}\/[0-9]{1,3}$/',
        ], [
            'date_consultation.required' => 'La date de consultation est obligatoire',
            'date_consultation.before_or_equal' => 'La date ne peut pas être dans le futur',
            'date_consultation.after' => 'Date invalide',
            'symptomes.max' => 'Les symptômes ne doivent pas dépasser 1000 caractères',
            'diagnostic.required' => 'Le diagnostic est obligatoire',
            'diagnostic.min' => 'Le diagnostic doit contenir au moins 10 caractères',
            'diagnostic.max' => 'Le diagnostic ne doit pas dépasser 2000 caractères',
            'traitement.required' => 'Le traitement est obligatoire',
            'traitement.min' => 'Le traitement doit contenir au moins 10 caractères',
            'traitement.max' => 'Le traitement ne doit pas dépasser 2000 caractères',
            'poids.numeric' => 'Le poids doit être un nombre',
            'poids.min' => 'Le poids semble trop faible',
            'poids.max' => 'Le poids semble trop élevé',
            'tension.regex' => 'Format tension invalide (ex: 12/8)',
        ]);
        
        \Log::info('Validation consultation réussie:', $validated);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Erreur validation consultation:', $e->errors());
        throw $e;
    }

    // Vérification du patient_id
    if (!$patient || !$patient->id) {
        \Log::error('Patient invalide ou ID manquant:', ['patient' => $patient]);
        return back()->with('error', 'Patient non valide');
    }
    
    // Ajout du patient_id aux données validées
    $validated['patient_id'] = $patient->id;
    \Log::info('Données finales avant création:', $validated);
    
    try {
        // Création directe sans passer par la relation pour éviter le problème
        $consultation = \App\Models\Consultation::create($validated);
        \Log::info('Consultation créée avec succès:', ['id' => $consultation->id]);
    } catch (\Exception $e) {
        \Log::error('Erreur création consultation:', ['error' => $e->getMessage(), 'data' => $validated]);
        return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
    }

    // Enregistrement de l'activité pour la traçabilité
    \App\Models\ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'Consultation ajoutée',
        'patient_name' => $patient->nom . ' ' . $patient->prenom,
    ]);

    // Redirection avec un message de succès
    return redirect()->route('patients.index')
        ->with('success', 'La consultation a été enregistrée avec succès dans le dossier de ' . $patient->nom);
}
public function generatePDF($id)
{

    $consultation = Consultation::with('patient')->findOrFail($id);


    $data = [
        'title' => 'Ordonnance Médicale - MediTrackD',
        'date' => date('d/m/Y'),
        'consultation' => $consultation
    ];


    $pdf = Pdf::loadView('consultations.pdf', $data);


    return $pdf->download('Ordonnance_'. $consultation->patient->nom .'.pdf');
}
}
