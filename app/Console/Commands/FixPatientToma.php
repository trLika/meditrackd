<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class FixPatientToma extends Command
{
    protected $signature = 'fix:patient-toma';
    protected $description = 'Fix the patient toma service assignment';

    public function handle()
    {
        $this->info('Searching for patient toma...');
        
        // Chercher le patient toma
        $tomaPatients = Patient::where('nom', 'like', '%toma%')
            ->orWhere('prenom', 'like', '%toma%')
            ->get();
            
        if ($tomaPatients->isEmpty()) {
            $this->error('No patient found with name containing "toma"');
            return;
        }
        
        foreach ($tomaPatients as $patient) {
            $this->info("Found patient: {$patient->nom} {$patient->prenom} (ID: {$patient->id})");
            $this->info("Current service_id: {$patient->service_id}");
            
            // Si le patient n'a pas de service_id, lui assigner le premier service disponible
            if (!$patient->service_id) {
                $firstService = Service::first();
                if ($firstService) {
                    $patient->service_id = $firstService->id;
                    $patient->save();
                    
                    $this->info("✓ Assigned service: {$firstService->name} (ID: {$firstService->id})");
                    
                    Log::info('Patient toma fixed:', [
                        'patient_id' => $patient->id,
                        'patient_name' => $patient->nom . ' ' . $patient->prenom,
                        'assigned_service_id' => $firstService->id,
                        'assigned_service_name' => $firstService->name
                    ]);
                } else {
                    $this->error('No services available in database');
                    return;
                }
            } else {
                // Vérifier si le service existe
                $service = Service::find($patient->service_id);
                if ($service) {
                    $this->info("✓ Patient already has valid service: {$service->name}");
                } else {
                    // Le service_id est invalide, assigner le premier service
                    $firstService = Service::first();
                    $patient->service_id = $firstService->id;
                    $patient->save();
                    
                    $this->info("✓ Fixed invalid service_id. Assigned: {$firstService->name} (ID: {$firstService->id})");
                    
                    Log::info('Patient toma service fixed:', [
                        'patient_id' => $patient->id,
                        'patient_name' => $patient->nom . ' ' . $patient->prenom,
                        'old_service_id' => $patient->service_id,
                        'new_service_id' => $firstService->id,
                        'new_service_name' => $firstService->name
                    ]);
                }
            }
        }
        
        $this->info('Patient toma fix completed!');
    }
}
