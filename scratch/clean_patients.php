<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Début de la suppression des patients...\n";

$services = Service::all();

foreach ($services as $service) {
    echo "Traitement du service : {$service->name} (ID: {$service->id})\n";
    
    // Récupérer les IDs des 2 premiers patients pour ce service
    $keepIds = Patient::where('service_id', $service->id)
        ->orderBy('id', 'asc')
        ->limit(2)
        ->pluck('id')
        ->toArray();
    
    if (empty($keepIds)) {
        echo "  Aucun patient dans ce service.\n";
        continue;
    }
    
    echo "  Conservation des IDs : " . implode(', ', $keepIds) . "\n";
    
    // Supprimer les autres patients de ce service
    $deletedCount = Patient::where('service_id', $service->id)
        ->whereNotIn('id', $keepIds)
        ->delete();
        
    echo "  {$deletedCount} patients supprimés pour ce service.\n";
}

// Supprimer les patients qui n'ont pas de service assigné (si il y en a)
$orphansCount = Patient::whereNull('service_id')->delete();
if ($orphansCount > 0) {
    echo "{$orphansCount} patients sans service supprimés.\n";
}

echo "Opération terminée.\n";
