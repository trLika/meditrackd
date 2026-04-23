<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateMedecinKate extends Seeder
{
    public function run(): void
    {
        // Créer Dr Kate si elle n'existe pas déjà
        User::firstOrCreate(
            ['email' => 'dr.kate@meditrackd.com'],
            [
                'name' => 'Dr. Kate',
                'password' => Hash::make('password123'),
            ]
        )->assignRole('medecin');
    }
}
