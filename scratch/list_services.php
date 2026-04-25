<?php

require dirname(__DIR__).'/vendor/autoload.php';
$app = require_once dirname(__DIR__).'/bootstrap/app.php';

use App\Models\Service;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Listing all services:\n";
$services = Service::all();
foreach ($services as $s) {
    echo "ID: {$s->id}, Name: {$s->name}\n";
}
