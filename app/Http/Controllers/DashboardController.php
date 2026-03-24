<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
class DashboardController extends Controller
{
    public function index()
    {
        // Plus tard, tu pourras envoyer des stats ici (ex: nombre de patients)
        $totalPatients = Patient::count();

        return view('dashboard', compact('totalPatients'));
    }
}
