<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DuenoGimnasio;
use App\Models\Gimnasio;
use App\Models\TipoMembresia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DuenoGimnasioSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarse de que el rol dueño existe
        $duenoRole = Role::where('name', 'dueño')->first();
        
        if (!$duenoRole) {
            $this->call(RolesAndPermissionsSeeder::class);
            $duenoRole = Role::where('name', 'dueño')->first();
        }

        // Crear el usuario dueño
        $user = User::create([
            'name' => 'dueno',
            'email' => 'dueno@outlook.es',
            'password' => Hash::make('gym'),
            'email_verified_at' => now(),
            'rol' => 'dueño',
            'configuracion_completa' => true,
            'telefono' => '0999999999',
            'direccion' => 'Calle Principal 123, Ciudad',
            'foto_perfil' => null
        ]);
        $user->assignRole('dueño');

        // Crear el dueño del gimnasio
        $dueno = DuenoGimnasio::create([
            'user_id' => $user->id,
            'nombre_comercial' => 'GymFit Center',
            'telefono_gimnasio' => '0999999999',
            'direccion_gimnasio' => 'Calle Principal 123, Ciudad',
            'logo' => null
        ]);

        // Crear el gimnasio
        $gimnasio = Gimnasio::create([
            'nombre' => 'GymFit Center',
            'direccion' => 'Calle Principal 123, Ciudad',
            'telefono' => '0999999999',
            'descripcion' => 'Tu gimnasio de confianza con equipamiento moderno',
            'logo' => null,
            'dueno_id' => $dueno->id_dueno,
            'estado' => true
        ]);

        // Crear tipos de membresía
        TipoMembresia::create([
            'gimnasio_id' => $gimnasio->id_gimnasio,
            'nombre' => 'Membresía Mensual',
            'descripcion' => 'Acceso ilimitado por 30 días',
            'precio' => 49.99,
            'duracion_dias' => 30,
            'tipo' => 'mensual',
            'estado' => true,
        ]);

        TipoMembresia::create([
            'gimnasio_id' => $gimnasio->id_gimnasio,
            'nombre' => 'Membresía Anual',
            'descripcion' => 'Acceso ilimitado por 1 año',
            'precio' => 499.99,
            'duracion_dias' => 365,
            'tipo' => 'anual',
            'estado' => true,
        ]);

        TipoMembresia::create([
            'gimnasio_id' => $gimnasio->id_gimnasio,
            'nombre' => 'Plan por Visitas',
            'descripcion' => '10 visitas al gimnasio',
            'precio' => 99.99,
            'tipo' => 'visitas',
            'numero_visitas' => 10,
            'estado' => true,
        ]);
    }
} 