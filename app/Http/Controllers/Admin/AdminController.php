<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Tu peux renvoyer vers une vue dédiée à l'administration
     $services = \App\Models\Service::all();
        return view('admin.services.index', compact('services'));
    }
}
