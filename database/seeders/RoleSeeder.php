<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. Création des rôles
        $admin = Role::findOrCreate('admin');
        $medecin = Role::findOrCreate('medecin');
        $stagiaire = Role::findOrCreate('stagiaire');

        // 2. Assignation de l'admin
        // Remplace 'admin@med.com' par l'email de ton compte principal
        $user = User::where('email', 'admin@med.com')->first();
        if ($user) {
            $user->assignRole($admin);
        }
    }
}
