<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarse de que el rol admin existe
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->call(RolesAndPermissionsSeeder::class);
            $adminRole = Role::where('name', 'admin')->first();
        }

        // Crear usuario administrador 1
        $user1 = User::create([
            'name' => 'Carlos',
            'email' => 'cangulo009@outlook.es',
            'password' => Hash::make('gym'),
            'email_verified_at' => now(),
            'rol' => 'admin'
        ]);
        $user1->assignRole('admin');

        // Crear usuario administrador 2
        $user2 = User::create([
            'name' => 'Jahxs',
            'email' => 'jahxs2328@gmail.com',
            'password' => Hash::make('gym'),
            'email_verified_at' => now(),
            'rol' => 'admin'
        ]);
        $user2->assignRole('admin');
    }
} 