<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DoctorRegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $services = Service::all();
        
        // Debug pour vérifier les services
        \Log::info('Doctor Registration Form - Services loaded:', [
            'services_count' => $services->count(),
            'services_list' => $services->pluck('name', 'id')->toArray()
        ]);
        
        return view('auth.doctor-register', compact('services'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $rules = [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:medecin,stagiaire'],
        ];

        // Le service_id est requis uniquement pour les médecins
        if ($request->role === 'medecin') {
            $rules['service_id'] = ['required', 'exists:services,id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->prenom . ' ' . $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assigner le rôle selon le choix
        $user->assignRole($request->role);

        // Assigner le service uniquement pour les médecins
        if ($request->role === 'medecin') {
            $service = Service::find($request->service_id);
            if ($service) {
                $user->services()->attach($service->id);
                \Log::info('Service assigned to new doctor:', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'service_id' => $service->id,
                    'service_name' => $service->name
                ]);
            } else {
                \Log::error('Service not found during registration:', ['service_id' => $request->service_id]);
            }
        }

        // DEBUG: Vérifier l'assignation
        \Log::info('New user registration complete:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $request->role,
            'assigned_services' => $user->services()->pluck('name', 'id')->toArray()
        ]);

        // Connecter automatiquement l'utilisateur
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Inscription réussie ! Bienvenue dans MediTrackD.');
    }
}
