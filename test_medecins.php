<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test direct des médecins
$medecins = App\Models\User::role('medecin')->get();

echo "=== TEST MÉDECINS ===" . PHP_EOL;
echo "Nombre de médecins: " . $medecins->count() . PHP_EOL;

foreach ($medecins as $medecin) {
    echo "- " . $medecin->name . " (" . $medecin->email . ")" . PHP_EOL;
}

echo PHP_EOL . "=== TEST SERVICES ===" . PHP_EOL;
$services = App\Models\Service::with('users')->get();
echo "Nombre de services: " . $services->count() . PHP_EOL;

foreach ($services as $service) {
    echo "- " . $service->name . " (médecins: " . $service->users->count() . ")" . PHP_EOL;
}
