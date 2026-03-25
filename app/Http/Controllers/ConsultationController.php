<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Patient $patient)
{

    return view('consultations.create', compact('patient'));
}
public function store(Request $request, Patient $patient)
{
    // 1. Validation des données (Très important pour le jury !)
    $request->validate([
        'date_consultation' => 'required|date',
        'diagnostic' => 'required|string',
        'traitement' => 'required|string',
        'poids' => 'nullable|numeric',
        'tension' => 'nullable|string',
    ]);

    // 2. Création de la consultation liée au patient
    $patient->consultations()->create([
        'date_consultation' => $request->date_consultation,
        'symptomes' => $request->symptomes,
        'diagnostic' => $request->diagnostic,
        'traitement' => $request->traitement,
        'poids' => $request->poids,
        'tension' => $request->tension,
    ]);

    // 3. Redirection avec un message de succès
    return redirect()->route('patients.index')
        ->with('success', 'La consultation a été enregistrée avec succès dans le dossier de ' . $patient->nom);
}
}
