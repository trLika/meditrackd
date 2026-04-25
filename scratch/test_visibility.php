<?php

require dirname(__DIR__).'/vendor/autoload.php';
$app = require_once dirname(__DIR__).'/bootstrap/app.php';

use App\Models\User;
use App\Models\Patient;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('name', 'Mamadou Sima')->first();
if (!$user) {
    echo "User Mamadou Sima not found!\n";
    exit;
}

$userServices = $user->services()->pluck('services.id');
echo "User: {$user->name}, Services IDs: " . $userServices->implode(', ') . "\n";

$patients = Patient::whereIn('service_id', $userServices)->get();
echo "Patients visible to {$user->name} (" . $patients->count() . " total):\n";
foreach ($patients as $p) {
    echo "- {$p->nom} {$p->prenom} (Service ID: {$p->service_id})\n";
}

$allKates = Patient::where('nom', 'like', '%Kate%')->orWhere('prenom', 'like', '%Kate%')->get();
echo "\nAll 'Kate' patients in DB:\n";
foreach ($allKates as $k) {
    echo "- {$k->nom} {$k->prenom} (Service ID: " . ($k->service_id ?? 'NULL') . ")\n";
}
