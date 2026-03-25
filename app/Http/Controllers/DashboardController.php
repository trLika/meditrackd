<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
class DashboardController extends Controller
{

public function index()
{
    $totalPatients = Patient::count();
    $consultationsToday = \App\Models\Consultation::whereDate('created_at', today())->count();
    $recentPatients = Patient::latest()->take(5)->get();

    // AJOUTE CETTE PARTIE POUR LE GRAPHIQUE
    $groupesSanguins = Patient::select('groupe_sanguin', \DB::raw('count(*) as total'))
        ->groupBy('groupe_sanguin')
        ->get();

    // N'oublie pas d'ajouter 'groupesSanguins' dans le compact
    return view('dashboard', compact('totalPatients', 'consultationsToday', 'recentPatients', 'groupesSanguins'));
}


}

