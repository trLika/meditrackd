<?php

require dirname(__DIR__).'/vendor/autoload.php';
$app = require_once dirname(__DIR__).'/bootstrap/app.php';

use App\Models\Patient;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Patients and their Service IDs:\n";
$patients = Patient::all();
foreach ($patients as $p) {
    echo "Patient: {$p->nom} {$p->prenom}, Service ID: " . ($p->service_id ?? 'NULL') . "\n";
}

echo "\nChecking Users and their assigned Services:\n";
$users = User::all();
foreach ($users as $u) {
    echo "User: {$u->name}, Services: " . $u->services->pluck('name')->implode(', ') . " (IDs: " . $u->services->pluck('id')->implode(', ') . ")\n";
}

echo "\nChecking if any patients have service_id = NULL:\n";
$nullPatients = Patient::whereNull('service_id')->count();
echo "Total patients with NULL service_id: $nullPatients\n";
