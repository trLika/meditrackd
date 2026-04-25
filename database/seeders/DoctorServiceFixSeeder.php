<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class DoctorServiceFixSeeder extends Seeder
{
    public function run()
    {
        // Récupérer tous les médecins qui n'ont pas de services
        $doctorsWithoutServices = User::whereHas('roles', function($query) {
            $query->where('name', 'medecin');
        })->whereDoesntHave('services')->get();

        Log::info('Doctor Service Fix - Found doctors without services:', [
            'count' => $doctorsWithoutServices->count()
        ]);

        // Récupérer tous les services disponibles
        $services = Service::all();
        
        if ($services->isEmpty()) {
            Log::error('No services found in database');
            return;
        }

        foreach ($doctorsWithoutServices as $doctor) {
            // Assigner le premier service disponible (Cardiologie par défaut)
            $defaultService = $services->first();
            
            $doctor->services()->attach($defaultService->id);
            
            Log::info('Service assigned to existing doctor:', [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'service_id' => $defaultService->id,
                'service_name' => $defaultService->name
            ]);
        }

        Log::info('Doctor Service Fix - Process completed');
    }
}
