<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Affiche la liste des services.
     */
    public function index()
    {
        $services = Service::all();
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
     * Supprime un service.
     */
    public function destroy(Service $service)
{
    dd('La méthode destroy a bien été appelée !');
    // 1. Vérification des patients (c'est bon)
    if ($service->patients()->count() > 0) {
        return back()->with('error', 'Impossible de supprimer ce service : il contient encore des patients.');
    }

    // 2. Suppression du service (c'est bon)
    $service->delete();

    // 3. LA CORRECTION : Utilise le nom de route correct affiché dans ton terminal !
    // Ton terminal affiche : admin/services ... admin.services.index
    return redirect()->route('admin.services.index')->with('success', 'Service supprimé avec succès.');
}
}
