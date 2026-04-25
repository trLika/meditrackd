<?php

require dirname(__DIR__).'/vendor/autoload.php';
$app = require_once dirname(__DIR__).'/bootstrap/app.php';

use App\Models\Service;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIXING PATIENT SERVICE IDS AND CLEANING DUPLICATES ===\n";

// 1. Get all services grouped by name
$servicesByName = Service::all()->groupBy('name');

foreach ($servicesByName as $name => $duplicates) {
    if ($duplicates->count() > 1) {
        echo "Processing duplicate service: $name\n";
        
        // Keep the one with the lowest ID (usually the original)
        $keep = $duplicates->sortBy('id')->first();
        $others = $duplicates->where('id', '!=', $keep->id);
        
        echo "  Keeping ID: {$keep->id}\n";
        
        foreach ($others as $other) {
            echo "  Merging ID: {$other->id} into {$keep->id}\n";
            
            // Transfer Patients
            $updatedPatients = Patient::where('service_id', $other->id)->update(['service_id' => $keep->id]);
            echo "    Transferred $updatedPatients patients.\n";
            
            // Transfer User associations (pivot table)
            $users = DB::table('service_user')->where('service_id', $other->id)->get();
            foreach ($users as $user) {
                // Check if user already associated with the kept service
                $exists = DB::table('service_user')
                    ->where('service_id', $keep->id)
                    ->where('user_id', $user->user_id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('service_user')->insert([
                        'service_id' => $keep->id,
                        'user_id' => $user->user_id
                    ]);
                    echo "    Transferred User ID: {$user->user_id}\n";
                }
            }
            DB::table('service_user')->where('service_id', $other->id)->delete();
            
            // Finally delete the duplicate service
            $other->delete();
            echo "    Deleted duplicate service ID: {$other->id}\n";
        }
    }
}

echo "=== FIX COMPLETED ===\n";
