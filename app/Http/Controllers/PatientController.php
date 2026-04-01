<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Controllers\Controller;
class PatientController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');// On s'assure que toutes les méthodes de ce contrôleur nécessitent une authentification
    }

public function index(Request $request)
{
    $query = Patient::query();


    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', '%' . $search . '%')
              ->orWhere('prenom', 'like', '%' . $search . '%')
              ->orWhere('telephone', 'like', '%' . $search . '%');
        });
    }

if ($request->query('critique') == '1') {
        $query->where('is_critique', 1);
    }

    if ($request->get('filter') == 'today') {
        $query->whereHas('consultations', function($q) {
            $q->whereDate('created_at', today());
        });
    }

    $patients = $query->orderBy('nom', 'asc')->paginate(10);

    return view('patients.index', compact('patients'));
}


public function create()
{
    return view('patients.create');
}


public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'sexe' => 'required',
        'date_naissance'=>'required|date',
        'telephone' => 'nullable',
        'adresse' => 'nullable',
        'groupe_sanguin' => 'nullable',
        'antecedents' => 'nullable',
        'is_critique' => 'boolean'
    ]);


    $validated['is_critique'] = $request->has('is_critique');

    Patient::create($validated);

    return redirect()->route('patients.index')->with('success', 'Patient ajouté !');
}

public function edit($id)
{
    // On récupère le patient par son ID
    $patient = Patient::findOrFail($id);

    // Vérification de sécurité : Seul un stagiaire est bloqué
    // Si tu es médecin, cette condition est ignorée
    if (auth()->user()->role === 'stagiaire') {
        return redirect()->route('patients.index')
            ->with('error', 'Action non autorisée pour les stagiaires.');
    }

    // Si on arrive ici, c'est qu'on est médecin ou admin
    return view('patients.edit', compact('patient'));
}


public function show($id)//fonction pour afficher les détails d'un patient et ses consultations associées
{

    $patient = Patient::findOrFail($id);
    $consultations = $patient->consultations()->orderBy('date_consultation', 'desc')->get();
 \App\Models\ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'Consultation du dossier',
        'patient_name' => $patient->nom . ' ' . $patient->prenom,
    ]);//fonction de tracabilite

    return view('patients.show', compact('patient', 'consultations'));
}
public function destroy($id)
{
    $patient = Patient::findOrFail($id);
    $patient->delete();

    return redirect()->route('patients.index')->with('success', 'Patient supprimé avec succès.');
}
//la fonction de mise a jour des donneees patients
public function update(Request $request, Patient $patient)
{
    if (auth()->user()->role === 'stagiaire') {
        abort(403, 'Action non autorisée.');
    }
    $validated = $request->validate([
        'nom' => 'required',
        'prenom' => 'required',
        'sexe' => 'required',
        'telephone' => 'nullable',
        'adresse' => 'nullable',
        'groupe_sanguin' => 'nullable',
        'antecedents' => 'nullable', // Très important
    ]);

    // Gestion spécifique de la checkbox (si décochée, elle n'est pas envoyée par le navigateur)
    $validated['is_critique'] = $request->has('is_critique');

    $patient->update($validated);

    //restriction de la tracabilite
    // Enregistrement de l'activité pour la traçabilité
\App\Models\ActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'Mise à jour dossier',
    'patient_name' => $patient->nom . ' ' . $patient->prenom,
]);

    return redirect()->route('patients.index')->with('success', 'Dossier patient mis à jour avec succès !');
}

}


