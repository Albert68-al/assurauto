<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        // Gestion des utilisateurs
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        
        // Configuration système
        Permission::create(['name' => 'manage settings']);
        Permission::create(['name' => 'manage backups']);
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);
        
        // Gestion des produits
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        
        // Gestion des clients
        Permission::create(['name' => 'view clients']);
        Permission::create(['name' => 'create clients']);
        Permission::create(['name' => 'edit clients']);
        Permission::create(['name' => 'delete clients']);
        
        // Gestion des polices
        Permission::create(['name' => 'view policies']);
        Permission::create(['name' => 'create policies']);
        Permission::create(['name' => 'edit policies']);
        Permission::create(['name' => 'delete policies']);
        
        // Gestion des sinistres
        Permission::create(['name' => 'view claims']);
        Permission::create(['name' => 'create claims']);
        Permission::create(['name' => 'process claims']);
        Permission::create(['name' => 'approve claims']);
        
        // Gestion des paiements
        Permission::create(['name' => 'view payments']);
        Permission::create(['name' => 'create payments']);
        Permission::create(['name' => 'validate payments']);
        Permission::create(['name' => 'export payments']);

        // Créer les rôles et attribuer les permissions
        // Super Admin
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Administrateur
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view products', 'create products', 'edit products',
            'view clients', 'create clients', 'edit clients',
            'view policies', 'create policies', 'edit policies',
            'view claims', 'process claims',
            'view payments', 'validate payments', 'export payments'
        ]);

        // Agent d'assurance
        $agent = Role::create(['name' => 'agent']);
        $agent->givePermissionTo([
            'view clients', 'create clients', 'edit clients',
            'view policies', 'create policies',
            'view claims', 'create claims',
            'view payments'
        ]);

        // Expert sinistre
        $expert = Role::create(['name' => 'expert']);
        $expert->givePermissionTo([
            'view claims', 'process claims', 'approve claims',
            'view policies'
        ]);

        // Comptable
        $accountant = Role::create(['name' => 'comptable']);
        $accountant->givePermissionTo([
            'view payments', 'create payments', 'validate payments', 'export payments',
            'view policies'
        ]);

        // Client
        $client = Role::create(['name' => 'client']);
        $client->givePermissionTo([
            'view policies',
            'view claims',
            'view payments'
        ]);
    }
}