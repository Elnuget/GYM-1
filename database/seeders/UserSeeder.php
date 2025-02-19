<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Carlos',
            'email' => 'cangulo009@outlook.es',
            'password' => Hash::make('gym'),
            'email_verified_at' => now(),
            'rol' => 'admin'
        ]);

        User::create([
            'name' => 'Jahxs',
            'email' => 'jahxs2328@gmail.com',
            'password' => Hash::make('gym'),
            'email_verified_at' => now(),
            'rol' => 'admin'
        ]);
    }
} 