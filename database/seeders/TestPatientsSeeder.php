<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class TestPatientsSeeder extends Seeder
{
    public function run()
    {
        // Créer des patients de test pour chaque service
        $services = Service::all();
        
        foreach ($services as $service) {
            // Créer 2-3 patients par service
            for ($i = 1; $i <= 3; $i++) {
                Patient::create([
                    'nom' => 'Patient' . $i . $service->name,
                    'prenom' => 'Test',
                    'sexe' => $i % 2 == 0 ? 'M' : 'F',
                    'date_naissance' => '1990-01-01',
                    'telephone' => '7000000' . $i,
                    'adresse' => 'Adresse test',
                    'groupe_sanguin' => 'O+',
                    'antecedents' => 'Aucun',
                    'service_id' => $service->id,
                    'is_critique' => false
                ]);
                
                Log::info('Test patient created:', [
                    'name' => 'Patient' . $i . $service->name . ' Test',
                    'service_id' => $service->id,
                    'service_name' => $service->name
                ]);
            }
        }
        
        Log::info('Test patients seeding completed');
    }
}
