<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Services\AdminStatsService;
use Illuminate\Http\Request;

class MedecinServiceController extends Controller
{
    protected $statsService;

    public function __construct(AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * Affiche la liste des médecins par département
     */
    public function index()
    {
        // Récupérer tous les services avec leurs médecins (optimisé)
        $services = Service::with(['users' => function($query) {
            $query->select('users.id', 'users.name', 'users.email');
        }])->get();
        
        // Récupérer tous les médecins (optimisé)
        $medecins = User::role('medecin')
            ->select('id', 'name', 'email')
            ->get();
        
        // Utiliser le service de statistiques pour des calculs optimisés
        $stats = $this->statsService->getAllStats();
        
        return view('admin.medecins-services.index', compact('services', 'medecins', 'stats'));
    }
    
    /**
     * Affiche les détails d'un service avec ses médecins
     */
    public function show(Service $service)
    {
        $service->load(['users' => function($query) {
            $query->select('users.id', 'users.name', 'users.email');
        }]);
        
        $medecinsDisponibles = User::role('medecin')
            ->select('id', 'name', 'email')
            ->whereNotIn('id', $service->users->pluck('id'))
            ->get();
            
        return view('admin.medecins-services.show', compact('service', 'medecinsDisponibles'));
    }
    
    /**
     * Assigner un médecin à un service (depuis le dashboard admin)
     */
    public function assignMedecinBulk(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'medecin_id' => 'required|exists:users,id'
        ]);
        
        $service = Service::find($request->service_id);
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
        
        // Rafraîchir les statistiques
        $this->statsService->refreshStats();
        
        return back()->with('success', 'Dr. ' . $medecin->name . ' a été assigné(e) au service ' . $service->name);
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
        
        // Rafraîchir les statistiques
        $this->statsService->refreshStats();
        
        return back()->with('success', 'Dr. ' . $medecin->name . ' a été assigné(e) au service ' . $service->name);
    }
    
    /**
     * Retirer un médecin d'un service
     */
    public function removeMedecin(Service $service, User $medecin)
    {
        $service->users()->detach($medecin->id);
        
        // Rafraîchir les statistiques
        $this->statsService->refreshStats();
        
        return back()->with('success', 'Dr. ' . $medecin->name . ' a été retiré(e) du service ' . $service->name);
    }
    
    /**
     * Affiche les médecins non assignés
     */
    public function medecinsNonAssignes()
    {
        $medecins = User::role('medecin')->get();
        $services = Service::with('users')->get();
        
        // Identifier les médecins non assignés
        $medecinsAssignes = $services->pluck('users')->flatten()->pluck('id')->unique();
        $medecinsNonAssignes = $medecins->whereNotIn('id', $medecinsAssignes);
        
        return view('admin.medecins-services.non-assignes', compact('medecinsNonAssignes', 'services'));
    }
    
    /**
     * Recherche de médecins
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('admin.medecins-services.index');
        }
        
        $services = Service::with(['users' => function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('email', 'like', '%' . $query . '%');
        }])->get();
        
        $medecins = User::role('medecin')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->get();
        
        $stats = [
            'total_services' => $services->count(),
            'total_medecins' => $medecins->count(),
            'medecins_assignes' => $services->sum(function($service) {
                return $service->users->count();
            }),
            'services_vides' => $services->filter(function($service) {
                return $service->users->count() === 0;
            })->count()
        ];
        
        return view('admin.medecins-services.index', compact('services', 'medecins', 'stats', 'query'));
    }
}
