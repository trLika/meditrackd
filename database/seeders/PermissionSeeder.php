<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles
        $roles = [
            'administrateur',
            'medecin', 
            'patient',
            'stagiaire'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Créer quelques permissions si nécessaire
        $permissions = [
            'manage-users',
            'manage-services',
            'view-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assigner les permissions aux rôles
        $adminRole = Role::findByName('administrateur');
        $adminRole->givePermissionTo(Permission::all());

        $medecinRole = Role::findByName('medecin');
        $medecinRole->givePermissionTo(['view-patients', 'create-patients', 'edit-patients']);
    }
}
