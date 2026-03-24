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

public function edit($id)
{
    $patient = Patient::findOrFail($id);
    return view('patients.edit', compact('patient'));
}


public function show($id)
{
    $patient = Patient::findOrFail($id);
    return view('patients.show', compact('patient'));
}

public function destroy($id)
{
    $patient = Patient::findOrFail($id);
    $patient->delete();

    return redirect()->route('patients.index')->with('success', 'Patient supprimé avec succès.');
}

public function update(Request $request, $id)

{

    $patient = Patient::findOrFail($id);


    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',

        'telephone' => 'required|unique:patients,telephone,' . $id,
    ]);


    $patient->update($request->all());


    return redirect()->route('patients.index')->with('success', 'Le patient a été mis à jour avec succès !');
}

}


