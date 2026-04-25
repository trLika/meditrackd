<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Déterminer si l'utilisateur est admin ou médecin
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        // DEBUG: Forcer l'admin pour tester
        if ($user->name === 'Administrateur') {
            $isAdmin = true;
        }
        
        \Log::info('Dashboard Debug:', [
            'user_name' => $user->name,
            'user_roles' => $user->getRoleNames()->toArray(),
            'has_admin_role' => $user->hasRole('admin'),
            'forced_isAdmin' => $isAdmin
        ]);
        
        if ($isAdmin) {
            // Statistiques générales pour l'admin
            $totalPatients = Patient::count();
            $consultationsToday = Consultation::whereDate('created_at', today())->count();
            $criticalCases = Patient::where('is_critique', true)->count();
            $recentPatients = Patient::latest()->take(5)->get();
            $groupesSanguins = Patient::select('groupe_sanguin', DB::raw('count(*) as total'))
                ->groupBy('groupe_sanguin')
                ->get();
            $recentLogs = ActivityLog::with('user')->latest()->take(20)->get();
            $userServices = null;
            
            // DEBUG: Log des valeurs
            \Log::info('Dashboard Admin Stats:', [
                'totalPatients' => $totalPatients,
                'consultationsToday' => $consultationsToday,
                'criticalCases' => $criticalCases,
                'recentPatients_count' => $recentPatients->count(),
                'groupesSanguins_count' => $groupesSanguins->count(),
                'recentLogs_count' => $recentLogs->count()
            ]);
        } else {
            // Statistiques filtrées pour les médecins
            $userServices = Auth::user()->services;
            $userServicesIds = $userServices->pluck('id');
            
            \Log::info('Doctor Dashboard Stats:', [
                'user_name' => $user->name,
                'user_services_count' => $userServices->count(),
                'user_services_ids' => $userServicesIds->toArray(),
                'user_services_names' => $userServices->pluck('name')->toArray()
            ]);
            
            $totalPatients = Patient::whereIn('service_id', $userServicesIds)->count();
            $consultationsToday = Consultation::whereHas('patient', function($query) use ($userServicesIds) {
                $query->whereIn('service_id', $userServicesIds);
            })->whereDate('created_at', today())->count();
            $criticalCases = Patient::whereIn('service_id', $userServicesIds)->where('is_critique', true)->count();
            $recentPatients = Patient::whereIn('service_id', $userServicesIds)->latest()->take(5)->get();
            $groupesSanguins = Patient::whereIn('service_id', $userServicesIds)
                ->select('groupe_sanguin', DB::raw('count(*) as total'))
                ->groupBy('groupe_sanguin')
                ->get();
            $recentLogs = ActivityLog::with('user')->latest()->take(10)->get();
            
            \Log::info('Doctor Dashboard Results:', [
                'totalPatients' => $totalPatients,
                'consultationsToday' => $consultationsToday,
                'criticalCases' => $criticalCases,
                'recentPatients_count' => $recentPatients->count(),
                'groupesSanguins_count' => $groupesSanguins->count()
            ]);
        }
        
        return view('dashboard', compact(
            'totalPatients', 
            'consultationsToday', 
            'criticalCases',
            'groupesSanguins', 
            'recentPatients',
            'recentLogs',
            'isAdmin',
            'userServices'
        ));
    }
}

