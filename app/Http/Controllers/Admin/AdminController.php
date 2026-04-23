<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminStatsService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $statsService;

    public function __construct(AdminStatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function index()
    {
        // Récupérer les statistiques avec cache pour optimiser les performances
        $stats = cache()->remember('admin.stats', 300, function () {
            return $this->statsService->getAllStats();
        });

        // Afficher le tableau de bord d'administration avec les statistiques
        return view('admin.index', compact('stats'));
    }
}
