<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Service; // Import nécessaire
use App\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Patient::query();

        // Restriction : Si l'utilisateur est médecin (non-admin), on filtre par ses services
        if (Auth::user()->role !== 'admin') {
            $userServices = Auth::user()->services()->pluck('id');
            $query->whereIn('service_id', $userServices);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('prenom', 'like', '%' . $search . '%')
                  ->orWhere('telephone', 'like', '%' . $search . '%');
            });
        }

        $patients = $query->orderBy('nom', 'asc')->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
      $services = \App\Models\Service::all();  // Chargement des services pour le select
        return view('patients.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'telephone' => 'required|string|regex:/^[67][0-9]{7}$/',
            'service_id' => 'required|exists:services,id', // Validation du service
            'is_critique' => 'boolean'
        ]);

        $validated['is_critique'] = $request->has('is_critique');
        $patient = Patient::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Création patient',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
        ]);

        return redirect()->route('patients.index')->with('success', 'Patient ajouté avec succès !');
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $services = Service::all(); // Nécessaire pour modifier le service
        return view('patients.edit', compact('patient', 'services'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id', // Ajout du service
            // ... autres validations existantes
        ]);

        $validated['is_critique'] = $request->has('is_critique');
        $patient->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mise à jour dossier',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
        ]);

        return redirect()->route('patients.index')->with('success', 'Dossier mis à jour.');
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        $consultations = $patient->consultations()->orderBy('date_consultation', 'desc')->get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Consultation du dossier',
            'patient_name' => $patient->nom . ' ' . $patient->prenom,
        ]);

        return view('patients.show', compact('patient', 'consultations'));
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient supprimé.');
    }
}
