<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:superadmin';
    protected $description = 'Create a super admin user';

    public function handle()
    {
        $user = User::firstOrCreate(
            ['email' => 'albert@assurauto.com'],
            [
                'name' => 'Albert',
                'password' => bcrypt('Albert03'),
                'email_verified_at' => now(),
                'nationality' => 'Congolaise',
                'id_number' => 'ADMIN001',
                'phone_number' => '+243996988012',
                'address' => 'Bujumbura',
                'city' => 'Bujumbura',
                'postal_code' => '00000',
            ]
        );

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $user->syncRoles([$superAdminRole]);

        $this->info('Super Admin mis à jour avec succès !');
        $this->info('Email: albert@assurauto.com');
        $this->info('Mot de passe: Albert03');
    }
}