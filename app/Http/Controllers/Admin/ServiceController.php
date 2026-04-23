<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Affiche la liste des services.
     */
    public function index()
    {
        $services = Service::with('users')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Enregistre un nouveau service en base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:services|max:255',
            'description' => 'nullable|string',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service créé avec succès.');
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Met à jour un service existant.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|max:255|unique:services,name,' . $service->id,
            'description' => 'nullable|string',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service mis à jour.');
    }

    /**
     * Assigner un médecin à un service
     */
    public function assignMedecin(Request $request, Service $service)
    {
        $request->validate([
            'medecin_id' => 'required|exists:users,id'
        ]);
        
        $medecin = User::find($request->medecin_id);
        
        // Vérifier que l'utilisateur est bien un médecin
        if (!$medecin->hasRole('medecin')) {
            return back()->with('error', 'Cet utilisateur n\'est pas un médecin.');
        }
        
        // Vérifier si déjà assigné
        if ($service->users()->where('user_id', $medecin->id)->exists()) {
            return back()->with('error', 'Ce médecin est déjà assigné à ce service.');
        }
        
        $service->users()->attach($medecin->id);
        
        return back()->with('success', 'Dr. ' . $medecin->name . ' a été assigné(e) au service ' . $service->name);
    }

    /**
     * Supprime un service.
     */
    public function destroy(Service $service)
    {
        // 1. Vérification des patients
        if ($service->patients()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce service : il contient encore des patients.');
        }

        // 2. Détacher les médecins avant suppression
        $service->users()->detach();

        // 3. Suppression du service
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service supprimé avec succès.');
    }
}
