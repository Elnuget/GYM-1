<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DuenoGimnasio;
use App\Models\Gimnasio;
use App\Models\TipoMembresia;
use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Pago;
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

        // Crear clientes de prueba
        $clientes = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => Hash::make('password'),
                'telefono' => '0998887776',
                'direccion' => 'Calle 123, Ciudad',
                'fecha_nacimiento' => '1990-05-15',
                'genero' => 'M',
                'ocupacion' => 'Ingeniero',
                'peso' => 75.5,
                'altura' => 175,
                'objetivo_fitness' => 'Ganar masa muscular',
                'condiciones_medicas' => 'Ninguna',
                'nivel_actividad' => 'intermedio',
                'membresia' => [
                    'tipo' => 'mensual',
                    'fecha_compra' => now(),
                    'renovacion' => true,
                    'pagos' => [
                        ['monto' => 25.00, 'fecha_pago' => now()->subDays(5)],
                        ['monto' => 24.99, 'fecha_pago' => now()]
                    ]
                ]
            ],
            [
                'name' => 'María García',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'telefono' => '0997776665',
                'direccion' => 'Avenida 456, Ciudad',
                'fecha_nacimiento' => '1992-08-20',
                'genero' => 'F',
                'ocupacion' => 'Profesora',
                'peso' => 65.0,
                'altura' => 165,
                'objetivo_fitness' => 'Pérdida de peso',
                'condiciones_medicas' => 'Ninguna',
                'nivel_actividad' => 'principiante',
                'membresia' => [
                    'tipo' => 'anual',
                    'fecha_compra' => now()->subMonths(2),
                    'renovacion' => true,
                    'pagos' => [
                        ['monto' => 250.00, 'fecha_pago' => now()->subMonths(2)],
                        ['monto' => 249.99, 'fecha_pago' => now()->subMonth()]
                    ]
                ]
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'telefono' => '0996665554',
                'direccion' => 'Plaza 789, Ciudad',
                'fecha_nacimiento' => '1988-03-10',
                'genero' => 'M',
                'ocupacion' => 'Empresario',
                'peso' => 80.0,
                'altura' => 180,
                'objetivo_fitness' => 'Mantenimiento',
                'condiciones_medicas' => 'Ninguna',
                'nivel_actividad' => 'avanzado',
                'membresia' => [
                    'tipo' => 'visitas',
                    'fecha_compra' => now()->subDays(15),
                    'renovacion' => false,
                    'visitas_permitidas' => 10,
                    'visitas_restantes' => 7,
                    'pagos' => [
                        ['monto' => 99.99, 'fecha_pago' => now()->subDays(15)]
                    ]
                ]
            ]
        ];

        foreach ($clientes as $clienteData) {
            // Crear usuario
            $user = User::create([
                'name' => $clienteData['name'],
                'email' => $clienteData['email'],
                'password' => $clienteData['password'],
                'email_verified_at' => now(),
                'rol' => 'cliente',
                'configuracion_completa' => true,
                'telefono' => $clienteData['telefono'],
                'direccion' => $clienteData['direccion'],
                'foto_perfil' => null
            ]);

            // Crear cliente
            $cliente = Cliente::create([
                'user_id' => $user->id,
                'gimnasio_id' => $gimnasio->id_gimnasio,
                'nombre' => $clienteData['name'],
                'email' => $clienteData['email'],
                'fecha_nacimiento' => $clienteData['fecha_nacimiento'],
                'telefono' => $clienteData['telefono'],
                'genero' => $clienteData['genero'],
                'ocupacion' => $clienteData['ocupacion']
            ]);

            // Obtener el tipo de membresía correspondiente
            $tipoMembresia = TipoMembresia::where('gimnasio_id', $gimnasio->id_gimnasio)
                ->where('tipo', $clienteData['membresia']['tipo'])
                ->first();

            // Calcular fecha de vencimiento
            $fechaVencimiento = null;
            if ($clienteData['membresia']['tipo'] !== 'visitas') {
                $fechaVencimiento = $clienteData['membresia']['fecha_compra']->copy()->addDays($tipoMembresia->duracion_dias);
            }

            // Crear membresía
            $membresia = Membresia::create([
                'id_usuario' => $user->id,
                'id_tipo_membresia' => $tipoMembresia->id_tipo_membresia,
                'precio_total' => $tipoMembresia->precio,
                'saldo_pendiente' => $tipoMembresia->precio,
                'fecha_compra' => $clienteData['membresia']['fecha_compra'],
                'fecha_vencimiento' => $fechaVencimiento,
                'visitas_permitidas' => $clienteData['membresia']['visitas_permitidas'] ?? null,
                'visitas_restantes' => $clienteData['membresia']['visitas_restantes'] ?? null,
                'renovacion' => $clienteData['membresia']['renovacion']
            ]);

            // Crear pagos
            foreach ($clienteData['membresia']['pagos'] as $pagoData) {
                $pago = Pago::create([
                    'id_membresia' => $membresia->id_membresia,
                    'id_usuario' => $user->id,
                    'monto' => $pagoData['monto'],
                    'fecha_pago' => $pagoData['fecha_pago'],
                    'estado' => 'aprobado',
                    'id_metodo_pago' => 1, // Asumiendo que 1 es el ID del método de pago por defecto
                    'comprobante_url' => null,
                    'notas' => 'Pago de prueba',
                    'fecha_aprobacion' => $pagoData['fecha_pago']
                ]);

                // Actualizar saldo pendiente
                $membresia->saldo_pendiente -= $pagoData['monto'];
                $membresia->save();
            }
        }
    }
} 