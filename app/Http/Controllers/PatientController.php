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
    // Débogage : Afficher les données reçues
    \Log::info('Données patient reçues:', $request->all());

    // Nettoyer le numéro de téléphone avant validation
    $telephone = $request->telephone;
    $telephone = preg_replace('/[\s\-\(\)]/', '', $telephone); // Retirer espaces, tirets, parenthèses
    $telephone = preg_replace('/^\+223/', '', $telephone); // Retirer le préfixe +223
    $request->merge(['telephone' => $telephone]);

    $validated = $request->validate([
        'nom' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
        'prenom' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
        'sexe' => 'required|in:M,F',
        'date_naissance' => 'required|date|before:today|after:1900-01-01',
        'telephone' => 'required|string|regex:/^[67][0-9]{7}$/',
        'adresse' => 'nullable|string|max:500',
        'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
        'antecedents' => 'nullable|string|max:1000',
        'allergies' => 'nullable|string|max:500',
        'is_critique' => 'boolean'
    ], [
        'nom.required' => 'Le nom est obligatoire',
        'nom.regex' => 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes',
        'prenom.required' => 'Le prénom est obligatoire',
        'prenom.regex' => 'Le prénom ne doit contenir que des lettres, espaces, tirets et apostrophes',
        'sexe.required' => 'Le sexe est obligatoire',
        'sexe.in' => 'Veuillez choisir un sexe valide',
        'date_naissance.required' => 'La date de naissance est obligatoire',
        'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
        'date_naissance.after' => 'La date de naissance semble invalide',
        'telephone.required' => 'Le téléphone est obligatoire',
        'telephone.regex' => 'Format invalide. Le numéro doit commencer par 6 ou 7 et contenir 8 chiffres',
        'adresse.max' => 'L\'adresse ne doit pas dépasser 500 caractères',
        'groupe_sanguin.in' => 'Veuillez choisir un groupe sanguin valide',
        'antecedents.max' => 'Les antécédents ne doivent pas dépasser 1000 caractères',
        'allergies.max' => 'Les allergies ne doivent pas dépasser 500 caractères'
    ]);

    $validated['is_critique'] = $request->has('is_critique');

    // Débogage : Afficher les données validées avant création
    \Log::info('Données patient validées:', $validated);

    $patient = Patient::create($validated);

    // Débogage : Vérifier si le patient a été créé
    \Log::info('Patient créé avec ID:', ['id' => $patient->id, 'nom' => $patient->nom]);

    return redirect()->route('patients.index')->with('success', 'Patient ajouté avec succès !');
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

    // Nettoyer le numéro de téléphone avant validation
    $telephone = $request->telephone;
    $telephone = preg_replace('/[\s\-\(\)]/', '', $telephone); // Retirer espaces, tirets, parenthèses
    $telephone = preg_replace('/^\+223/', '', $telephone); // Retirer le préfixe +223
    $request->merge(['telephone' => $telephone]);

    $validated = $request->validate([
        'nom' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
        'prenom' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
        'sexe' => 'required|in:M,F',
        'date_naissance' => 'required|date|before:today|after:1900-01-01',
        'telephone' => 'required|string|regex:/^[67][0-9]{7}$/',
        'adresse' => 'nullable|string|max:500',
        'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
        'antecedents' => 'nullable|string|max:1000',
        'allergies' => 'nullable|string|max:500',
    ], [
        'nom.required' => 'Le nom est obligatoire',
        'nom.regex' => 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes',
        'prenom.required' => 'Le prénom est obligatoire',
        'prenom.regex' => 'Le prénom ne doit contenir que des lettres, espaces, tirets et apostrophes',
        'sexe.required' => 'Le sexe est obligatoire',
        'sexe.in' => 'Veuillez choisir un sexe valide',
        'date_naissance.required' => 'La date de naissance est obligatoire',
        'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
        'date_naissance.after' => 'La date de naissance semble invalide',
        'telephone.required' => 'Le téléphone est obligatoire',
        'telephone.regex' => 'Format invalide. Le numéro doit commencer par 6 ou 7 et contenir 8 chiffres',
        'adresse.max' => 'L\'adresse ne doit pas dépasser 500 caractères',
        'groupe_sanguin.in' => 'Veuillez choisir un groupe sanguin valide',
        'antecedents.max' => 'Les antécédents ne doivent pas dépasser 1000 caractères',
        'allergies.max' => 'Les allergies ne doivent pas dépasser 500 caractères'
    ]);

    $validated['is_critique'] = $request->has('is_critique');

    $patient->update($validated);

    \App\Models\ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'Mise à jour dossier',
        'patient_name' => $patient->nom . ' ' . $patient->prenom,
    ]);

    return redirect()->route('patients.index')->with('success', 'Dossier patient mis à jour avec succès !');
}

}


