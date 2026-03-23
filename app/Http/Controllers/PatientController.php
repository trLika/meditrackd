<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Controllers\Controller;
class PatientController extends Controller
{
    public function index()
{
    $patients = Patient::all();
   return view('patients.index', compact('patients'));


}

public function create()
{
    return view('patients.create');
}


public function store(Request $request)



{
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        // Ajout de l'unicité sur le téléphone ici :
        'telephone' => 'required|unique:patients,telephone',
        'groupe_sanguin' => 'required',
        'sexe' => 'required',
        'adresse' => 'nullable|string|max:255',
    ], [
        'nom.required' => 'Le nom est obligatoire.',
        'telephone.unique' => 'Ce numéro de téléphone est déjà attribué à un autre patient.',
        'sexe.required' => 'Le sexe est obligatoire.',
        'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',

    ]);

    Patient::create($request->all());


    return redirect()->route('patients.index')->with('success', 'Patient enregistré !');
}
// Pour le bouton "Modifier" (le crayon)
public function edit($id)
{
    $patient = Patient::findOrFail($id);
    return view('patients.edit', compact('patient'));
}

// Pour le bouton "Voir" (l'œil)
public function show($id)
{
    $patient = Patient::findOrFail($id);
    return view('patients.show', compact('patient'));
}

// Pour le bouton "Supprimer" (la poubelle)
public function destroy($id)
{
    $patient = Patient::findOrFail($id);
    $patient->delete();

    return redirect()->route('patients.index')->with('success', 'Patient supprimé avec succès.');
}

public function update(Request $request, $id)

{
    // 1. On cherche le patient dans la base
    $patient = Patient::findOrFail($id);

    // 2. On valide les nouvelles données
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        // On permet au patient de garder son numéro, mais on vérifie s'il ne prend pas celui d'un autre
        'telephone' => 'required|unique:patients,telephone,' . $id,
    ]);

    // 3. On met à jour les données
    $patient->update($request->all());

    // 4. On redirige vers la liste avec un message de succès
    return redirect()->route('patients.index')->with('success', 'Le patient a été mis à jour avec succès !');
}

}


