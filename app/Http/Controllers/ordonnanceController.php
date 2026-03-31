<?php

namespace App\Http\Controllers;

use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // La bibliothèque que tu as déjà

class OrdonnanceController extends Controller
{
    // Affiche le formulaire (depuis le dossier patient)
    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        return view('ordonnances.create', compact('patient'));
    }

    // Enregistre l'ordonnance en base de données
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'contenu' => 'required', // Les médicaments
        ]);

        $ordonnance = Ordonnance::create([
            'patient_id' => $request->patient_id,
            'contenu' => $request->contenu,
            'user_id' => auth()->id(), // Le médecin connecté
            'date_prescription' => now(),
        ]);

        return redirect()->route('patients.show', $request->patient_id)
                         ->with('success', 'Ordonnance générée avec succès');
    }

    // La méthode magique pour le PDF (que tu réutilises)
    public function generatePDF($id)
    {
        $ordonnance = Ordonnance::with('patient', 'user')->findOrFail($id);

        // On utilise une vue spécifique pour le design de l'ordonnance
        $pdf = Pdf::loadView('ordonnances.pdf_template', compact('ordonnance'));

        return $pdf->stream('Ordonnance_'.$ordonnance->patient->nom.'.pdf');
    }
}
