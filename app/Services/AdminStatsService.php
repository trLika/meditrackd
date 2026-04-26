<?php

namespace App\Services;

use App\Models\Service;
use App\Models\User;

class AdminStatsService
{
    /**
     * Calcule toutes les statistiques pour le dashboard admin
     */
    public function getAllStats(): array
    {
        return [
            'total_services' => $this->getTotalServices(),
            'total_medecins' => $this->getTotalMedecins(),
            'medecins_assignes' => $this->getMedecinsAssignes(),
            'services_vides' => $this->getServicesVides(),
            'total_utilisateurs' => $this->getTotalUtilisateurs(),
            'total_stagiaires' => $this->getTotalStagiaires(),
            'taux_assignation' => $this->getTauxAssignation(),
        ];
    }

    /**
     * Nombre total de services
     */
    public function getTotalServices(): int
    {
        return Service::count();
    }

    /**
     * Nombre total de médecins
     */
    public function getTotalMedecins(): int
    {
        return User::role('medecin')->count();
    }

    /**
     * Nombre de médecins assignés à au moins un service
     */
    public function getMedecinsAssignes(): int
    {
        return User::role('medecin')->whereHas('services')->count();
    }

    /**
     * Nombre de services sans médecins assignés
     */
    public function getServicesVides(): int
    {
        return Service::whereDoesntHave('users')->count();
    }

    /**
     * Nombre total d'utilisateurs
     */
    public function getTotalUtilisateurs(): int
    {
        return User::count();
    }

    /**
     * Nombre total de stagiaires
     */
    public function getTotalStagiaires(): int
    {
        return User::whereHas('roles', function($q) {
            $q->where('name', 'stagiaire');
        })->orWhere('role', 'stagiaire')->count();
    }

    /**
     * Taux d'assignation des médecins (en pourcentage)
     */
    public function getTauxAssignation(): float
    {
        $totalMedecins = $this->getTotalMedecins();
        if ($totalMedecins === 0) {
            return 0;
        }
        
        return round(($this->getMedecinsAssignes() / $totalMedecins) * 100, 1);
    }

    /**
     * Statistiques détaillées par service
     */
    public function getServicesStats(): array
    {
        return Service::withCount('users')
            ->with('users:id,name,email')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'medecins_count' => $service->users_count,
                    'medecins' => $service->users->map(function ($medecin) {
                        return [
                            'id' => $medecin->id,
                            'name' => $medecin->name,
                            'email' => $medecin->email,
                        ];
                    }),
                ];
            })
            ->toArray();
    }

    /**
     * Médecins non assignés avec leurs informations
     */
    public function getMedecinsNonAssignes(): array
    {
        return User::role('medecin')
            ->whereDoesntHave('services')
            ->get(['id', 'name', 'email'])
            ->toArray();
    }

    /**
     * Rafraîchit le cache des statistiques
     */
    public function refreshStats(): void
    {
        cache()->forget('admin.stats');
    }
}
