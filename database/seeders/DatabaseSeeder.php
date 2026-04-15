<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void

{

$this->call([
            ServiceSeeder::class,
        ]);
    // 1. Création de l'Administrateur
{
    \App\Models\User::create([
        'name' => 'Administrateur',
        'email' => 'admin@hopital.ml',
        'password' => bcrypt('secret123'), // C'est le mot de passe
        'role' => 'admin',
    ]);
}
    // 2. Création du Médecin
    \App\Models\User::create([
        'name' => 'Dr.Kate',
        'email' => 'medecin@med.com',
        'password' => bcrypt('password'),
        'role' => 'medecin',
    ]);

    // 3. Création du Stagiaire (pour tester les restrictions plus tard)
    \App\Models\User::create([
        'name' => 'Stagiaire Miya',
        'email' => 'stagiaire@med.com',
        'password' => bcrypt('password'),
        'role' => 'stagiaire',
    ]);
}
}

