<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Controllers\Controller;
class PatientController extends Controller
{
    public function index()
{
    $patients = Patient::all();
   return view('patients.index', compact('patients'));


}

}
