<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        DB::table('services')->insert([
            ['name' => 'Cardiologie'],
            ['name' => 'Pédiatrie'],
            ['name' => 'Neurologie'],
            ['name' => 'Gynécologie'],
            ['name' => 'Chirurgie'],
        ]);
    }
}
