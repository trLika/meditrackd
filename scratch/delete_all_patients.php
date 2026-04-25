<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Ordonnance;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Suppression de TOUS les patients et de leurs données liées...\n";

try {
    DB::beginTransaction();

    // Supprimer les ordonnances liées aux patients
    $ordonnancesCount = Ordonnance::query()->delete();
    echo "- {$ordonnancesCount} ordonnances supprimées.\n";

    // Supprimer les consultations liées aux patients
    $consultationsCount = Consultation::query()->delete();
    echo "- {$consultationsCount} consultations supprimées.\n";

    // Supprimer tous les patients
    $patientsCount = Patient::query()->delete();
    echo "- {$patientsCount} patients supprimés.\n";

    DB::commit();
    echo "Opération terminée avec succès.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Erreur lors de la suppression : " . $e->getMessage() . "\n";
}
