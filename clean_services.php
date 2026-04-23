<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== NETTOYAGE DES SERVICES DUPLIQUÉS ===" . PHP_EOL;

// 1. Lister tous les services avec leurs assignations
echo PHP_EOL . "1. ÉTAT ACTUEL:" . PHP_EOL;
$services = App\Models\Service::with('users')->get();
$serviceNames = [];

foreach ($services as $service) {
    $serviceNames[] = $service->name;
    echo "ID {$service->id}: {$service->name} ({$service->users->count()} médecins)" . PHP_EOL;
    foreach ($service->users as $user) {
        echo "    - {$user->name}" . PHP_EOL;
    }
}

// 2. Identifier les doublons
echo PHP_EOL . "2. DOUBLONS IDENTIFIÉS:" . PHP_EOL;
$duplicates = array_count_values($serviceNames);
foreach ($duplicates as $name => $count) {
    if ($count > 1) {
        echo "- {$name}: {$count} occurrences" . PHP_EOL;
    }
}

// 3. Nettoyer les doublons
echo PHP_EOL . "3. NETTOYAGE EN COURS..." . PHP_EOL;
$uniqueServices = [];
$servicesToDelete = [];

foreach ($services as $service) {
    if (!in_array($service->name, $uniqueServices)) {
        $uniqueServices[] = $service->name;
        echo "Garder: ID {$service->id} - {$service->name}" . PHP_EOL;
    } else {
        $servicesToDelete[] = $service;
        echo "À supprimer: ID {$service->id} - {$service->name}" . PHP_EOL;
    }
}

// 4. Supprimer les doublons
foreach ($servicesToDelete as $service) {
    // Vérifier s'il y a des médecins assignés
    if ($service->users()->count() > 0) {
        echo "Transfert des médecins du service {$service->id} vers le service original..." . PHP_EOL;
        
        // Trouver le service original
        $originalService = App\Models\Service::where('name', $service->name)
            ->where('id', '!=', $service->id)
            ->first();
        
        if ($originalService) {
            // Transférer les assignations
            foreach ($service->users as $user) {
                $alreadyAssigned = $originalService->users()->where('user_id', $user->id)->exists();
                if (!$alreadyAssigned) {
                    $originalService->users()->attach($user->id);
                    echo "  Transféré: {$user->name}" . PHP_EOL;
                }
            }
        }
    }
    
    // Supprimer le doublon
    $service->delete();
    echo "Supprimé: ID {$service->id} - {$service->name}" . PHP_EOL;
}

// 5. Vérifier l'état final
echo PHP_EOL . "4. ÉTAT FINAL:" . PHP_EOL;
$finalServices = App\Models\Service::with('users')->get();
foreach ($finalServices as $service) {
    echo "ID {$service->id}: {$service->name} ({$service->users->count()} médecins)" . PHP_EOL;
    foreach ($service->users as $user) {
        echo "    - {$user->name}" . PHP_EOL;
    }
}

echo PHP_EOL . "=== NETTOYAGE TERMINÉ ===" . PHP_EOL;
