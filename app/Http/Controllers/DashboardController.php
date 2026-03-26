<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
class DashboardController extends Controller
{

public function index()
{
    $totalPatients = \App\Models\Patient::count();
    $consultationsToday = \App\Models\Consultation::whereDate('created_at', today())->count();
    $recentPatients = Patient::latest()->take(5)->get();


    $groupesSanguins = Patient::select('groupe_sanguin', \DB::raw('count(*) as total'))
        ->groupBy('groupe_sanguin')
        ->get();

        $criticalCases = Patient::where('is_critique', true)->count();
 $recentLogs = \App\Models\ActivityLog::with('user')->latest()->take(20)->get();

   return view('dashboard', compact('totalPatients', 'consultationsToday', 'criticalCases',
   'groupesSanguins', 'recentPatients','recentLogs'));
}


}

