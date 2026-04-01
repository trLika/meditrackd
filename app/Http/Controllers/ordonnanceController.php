<?php

namespace App\Http\Controllers;

use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdonnanceController extends Controller
{
    // Affiche le formulaire (depuis le dossier patient)
   public function create(Request $request)
{
    $patient_id = $request->query('patient_id');
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


    public function generatePDF($id)
    {
        $ordonnance = Ordonnance::with('patient', 'user')->findOrFail($id);


        $pdf = Pdf::loadView('ordonnances.pdf_template', compact('ordonnance'));

        return $pdf->stream('Ordonnance_'.$ordonnance->patient->nom.'.pdf');
    }


public function edit($id)
{
    $ordonnance = Ordonnance::findOrFail($id);

    // Vérification : Si l'ID de l'utilisateur connecté n'est pas celui du créateur
    if (auth()->id() !== $ordonnance->user_id) {
        return redirect()->route('patients.show', $ordonnance->patient_id)
                         ->with('error', 'Action non autorisée : Vous n\'êtes pas l\'auteur de cette ordonnance.');
    }

    return view('ordonnances.edit', compact('ordonnance'));
}

public function update(Request $request, $id)
{
    // 1. Validation des données
    $request->validate([
        'contenu' => 'required|string',
        'date_prescription' => 'required|date',
    ]);

    // 2. Récupération de l'ordonnance
    $ordonnance = Ordonnance::findOrFail($id);

    // 3. SÉCURITÉ : Vérifier que l'utilisateur est l'auteur
    if (auth()->id() !== $ordonnance->user_id) {
        return redirect()->route('patients.show', $ordonnance->patient_id)
                         ->with('error', 'Accès refusé : Vous ne pouvez pas modifier une ordonnance dont vous n\'êtes pas l\'auteur.');
    }

    // 4. Mise à jour des données
    $ordonnance->update([
        'contenu' => $request->contenu,
        'date_prescription' => $request->date_prescription,
    ]);

    // 5. Redirection avec message de succès
    return redirect()->route('patients.show', $ordonnance->patient_id)
                     ->with('success', 'L\'ordonnance a été mise à jour avec succès.');
}

public function destroy($id)
{
    $ordonnance = Ordonnance::findOrFail($id);
    $patient_id = $ordonnance->patient_id;
    $ordonnance->delete();

    return redirect()->route('patients.show', $patient_id)
                     ->with('success', 'Ordonnance supprimée avec succès.');
}
}
