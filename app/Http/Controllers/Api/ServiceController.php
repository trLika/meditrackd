<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Seul l'admin peut voir tous les services
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $services = Service::with('users')->get();
        return response()->json($services);
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services',
            'description' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        // Seul l'admin peut créer des services
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $service = Service::create($request->all());
        return response()->json($service, 201);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        $user = Auth::user();
        
        // Seul l'admin peut voir les détails des services
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $service->load('users');
        return response()->json($service);
    }

    /**
     * Update the specified service.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:services,name,' . $service->id,
            'description' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        // Seul l'admin peut modifier les services
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $service->update($request->all());
        return response()->json($service);
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service)
    {
        $user = Auth::user();
        
        // Seul l'admin peut supprimer des services
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Vérifier s'il y a des patients associés
        if ($service->patients()->count() > 0) {
            return response()->json(['message' => 'Impossible de supprimer ce service : il contient des patients'], 422);
        }

        $service->delete();
        return response()->json(['message' => 'Service supprimé']);
    }

    /**
     * Assigner un médecin à un service
     */
    public function assignMedecin(Request $request, Service $service)
    {
        $request->validate([
            'medecin_id' => 'required|exists:users,id'
        ]);

        $user = Auth::user();
        
        // Seul l'admin peut assigner des médecins
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $medecin = User::find($request->medecin_id);
        
        // Vérifier que l'utilisateur est bien un médecin
        if (!$medecin->hasRole('medecin')) {
            return response()->json(['message' => 'Cet utilisateur n\'est pas un médecin'], 422);
        }

        // Assigner le médecin au service
        $service->users()->syncWithoutDetaching([$medecin->id]);

        return response()->json(['message' => 'Médecin assigné au service avec succès']);
    }

    /**
     * Retirer un médecin d'un service
     */
    public function removeMedecin(Service $service, User $medecin)
    {
        $user = Auth::user();
        
        // Seul l'admin peut retirer des médecins
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $service->users()->detach($medecin->id);
        return response()->json(['message' => 'Médecin retiré du service avec succès']);
    }
}
