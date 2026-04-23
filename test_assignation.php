<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DIAGNOSTIC COMPLET DE L'ASSIGNATION ===" . PHP_EOL;

// 1. Vérifier les services
echo PHP_EOL . "1. SERVICES DISPONIBLES:" . PHP_EOL;
$services = App\Models\Service::all();
foreach ($services as $service) {
    echo "- Service ID {$service->id}: {$service->name}" . PHP_EOL;
}

// 2. Vérifier les médecins
echo PHP_EOL . "2. MÉDECINS DISPONIBLES:" . PHP_EOL;
$medecins = App\Models\User::role('medecin')->get();
foreach ($medecins as $medecin) {
    echo "- {$medecin->name} (ID: {$medecin->id})" . PHP_EOL;
}

// 3. Vérifier l'état actuel des assignations
echo PHP_EOL . "3. ÉTAT ACTUEL DES ASSIGNATIONS:" . PHP_EOL;
$services = App\Models\Service::with('users')->get();
foreach ($services as $service) {
    echo "Service: {$service->name}" . PHP_EOL;
    echo "  Médecins assignés: {$service->users->count()}" . PHP_EOL;
    foreach ($service->users as $user) {
        echo "    - {$user->name}" . PHP_EOL;
    }
    echo PHP_EOL;
}

// 4. Test d'assignation directe
echo PHP_EOL . "4. TEST D'ASSIGNATION DIRECTE:" . PHP_EOL;
$cardiologie = App\Models\Service::find(1);
$drKate = App\Models\User::where('email', 'dr.kate@meditrackd.com')->first();

if ($cardiologie && $drKate) {
    echo "Cardiologie trouvée: {$cardiologie->name}" . PHP_EOL;
    echo "Dr. Kate trouvée: {$drKate->name}" . PHP_EOL;
    
    // Vérifier si déjà assignée
    $alreadyAssigned = $cardiologie->users()->where('user_id', $drKate->id)->exists();
    echo "Déjà assignée: " . ($alreadyAssigned ? 'OUI' : 'NON') . PHP_EOL;
    
    if (!$alreadyAssigned) {
        echo "Assignation en cours..." . PHP_EOL;
        $cardiologie->users()->attach($drKate->id);
        echo "Assignation effectuée!" . PHP_EOL;
    }
    
    // Vérifier après assignation
    $cardiologie->refresh();
    echo "Médecins après assignation: {$cardiologie->users->count()}" . PHP_EOL;
    foreach ($cardiologie->users as $user) {
        echo "  - {$user->name}" . PHP_EOL;
    }
} else {
    echo "ERREUR: Cardiologie ou Dr. Kate non trouvée" . PHP_EOL;
}

echo PHP_EOL . "=== FIN DU DIAGNOSTIC ===" . PHP_EOL;
