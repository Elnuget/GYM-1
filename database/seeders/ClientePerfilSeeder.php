<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use App\Models\MedidaCorporal;
use App\Models\ObjetivoCliente;
use App\Models\Membresia;
use App\Models\TipoMembresia;
use App\Models\Pago;
use App\Models\Asistencia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientePerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que el rol cliente existe
        $clienteRole = Role::where('name', 'cliente')->first();
        
        if (!$clienteRole) {
            $this->call(RolesAndPermissionsSeeder::class);
            $clienteRole = Role::where('name', 'cliente')->first();
        }

        // Obtener el primer gimnasio disponible
        $gimnasio = \App\Models\Gimnasio::first();
        
        if (!$gimnasio) {
            // Si no hay gimnasios, llamar al seeder de dueño de gimnasio
            $this->call(DuenoGimnasioSeeder::class);
            $gimnasio = \App\Models\Gimnasio::first();
        }

        // Obtener tipos de membresía
        $tipoMembresiaMensual = TipoMembresia::where('gimnasio_id', $gimnasio->id_gimnasio)
            ->where('tipo', 'mensual')
            ->first();
            
        $tipoMembresiaAnual = TipoMembresia::where('gimnasio_id', $gimnasio->id_gimnasio)
            ->where('tipo', 'anual')
            ->first();

        // Si no existen tipos de membresía, crearlos
        if (!$tipoMembresiaMensual) {
            $tipoMembresiaMensual = TipoMembresia::create([
                'gimnasio_id' => $gimnasio->id_gimnasio,
                'nombre' => 'Membresía Mensual',
                'descripcion' => 'Acceso ilimitado por 30 días',
                'precio' => 49.99,
                'duracion_dias' => 30,
                'tipo' => 'mensual',
                'estado' => true,
            ]);
        }
        
        if (!$tipoMembresiaAnual) {
            $tipoMembresiaAnual = TipoMembresia::create([
                'gimnasio_id' => $gimnasio->id_gimnasio,
                'nombre' => 'Membresía Anual',
                'descripcion' => 'Acceso ilimitado por 1 año',
                'precio' => 499.99,
                'duracion_dias' => 365,
                'tipo' => 'anual',
                'estado' => true,
            ]);
        }

        // CLIENTE COMPLETO
        // ----------------
        $user = User::create([
            'name' => 'Cliente Completo',
            'email' => 'cliente.completo@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'rol' => 'cliente',
            'configuracion_completa' => true,
            'telefono' => '0987654321',
            'direccion' => 'Av. Principal 456, Ciudad',
            'foto_perfil' => null
        ]);
        
        $user->assignRole('cliente');

        // Crear el cliente
        $cliente = Cliente::create([
            'user_id' => $user->id,
            'gimnasio_id' => $gimnasio->id_gimnasio,
            'nombre' => 'Cliente Completo',
            'email' => 'cliente.completo@example.com',
            'fecha_nacimiento' => '1995-06-15',
            'telefono' => '0987654321',
            'genero' => 'M',
            'ocupacion' => 'Desarrollador'
        ]);

        // Crear membresía anual
        $fechaCompra = Carbon::now()->subMonths(2);
        $fechaVencimiento = $fechaCompra->copy()->addDays($tipoMembresiaAnual->duracion_dias);
        
        $membresia = Membresia::create([
            'id_usuario' => $user->id,
            'id_tipo_membresia' => $tipoMembresiaAnual->id_tipo_membresia,
            'precio_total' => $tipoMembresiaAnual->precio,
            'saldo_pendiente' => 0, // Pagado completamente
            'fecha_compra' => $fechaCompra,
            'fecha_vencimiento' => $fechaVencimiento,
            'renovacion' => true
        ]);

        // Crear pagos para la membresía
        $pagos = [
            ['monto' => 200.00, 'fecha' => $fechaCompra],
            ['monto' => 200.00, 'fecha' => $fechaCompra->copy()->addDays(15)],
            ['monto' => 99.99, 'fecha' => $fechaCompra->copy()->addMonth()]
        ];

        foreach ($pagos as $pagoData) {
            Pago::create([
                'id_membresia' => $membresia->id_membresia,
                'id_usuario' => $user->id,
                'monto' => $pagoData['monto'],
                'fecha_pago' => $pagoData['fecha'],
                'estado' => 'aprobado',
                'id_metodo_pago' => 1, // Efectivo
                'comprobante_url' => null,
                'notas' => 'Pago de membresía anual',
                'fecha_aprobacion' => $pagoData['fecha']
            ]);
        }

        // Crear asistencias (últimos 2 meses, 3 veces por semana)
        $fechaInicio = Carbon::now()->subMonths(2);
        $diasSemana = [1, 3, 5]; // Lunes, Miércoles, Viernes
        
        $fechaActual = $fechaInicio->copy();
        while ($fechaActual->lessThanOrEqualTo(Carbon::now())) {
            if (in_array($fechaActual->dayOfWeek, $diasSemana)) {
                $horaEntrada = $fechaActual->copy()->setHour(rand(7, 10))->setMinute(rand(0, 59));
                $horaSalida = $horaEntrada->copy()->addHours(rand(1, 3));
                
                // Verificar la estructura de la tabla asistencias
                $columnas = DB::getSchemaBuilder()->getColumnListing('asistencias');
                
                $asistenciaData = [
                    'fecha' => $fechaActual->toDateString(),
                    'hora_entrada' => $horaEntrada,
                    'hora_salida' => $horaSalida,
                    'duracion_minutos' => $horaEntrada->diffInMinutes($horaSalida),
                    'estado' => 'activa',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                // Agregar campos según la estructura de la tabla
                if (in_array('cliente_id', $columnas)) {
                    $asistenciaData['cliente_id'] = $cliente->id_cliente;
                }
                
                if (in_array('id_cliente', $columnas)) {
                    $asistenciaData['id_cliente'] = $cliente->id_cliente;
                }
                
                if (in_array('gimnasio_id', $columnas)) {
                    $asistenciaData['gimnasio_id'] = $gimnasio->id_gimnasio;
                }
                
                if (in_array('id_gimnasio', $columnas)) {
                    $asistenciaData['id_gimnasio'] = $gimnasio->id_gimnasio;
                }
                
                DB::table('asistencias')->insert($asistenciaData);
            }
            $fechaActual->addDay();
        }

        // Crear historial de medidas corporales (últimos 3 meses)
        $fechas = [
            Carbon::now()->subMonths(3),
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonths(1),
            Carbon::now()
        ];

        // Simular progreso en las medidas
        $pesos = [85.5, 83.2, 81.0, 79.5];
        $cinturas = [95.0, 93.5, 91.0, 89.5];
        
        foreach ($fechas as $index => $fecha) {
            MedidaCorporal::create([
                'cliente_id' => $cliente->id_cliente,
                'fecha_medicion' => $fecha,
                'peso' => $pesos[$index],
                'altura' => 178.0,
                'cuello' => 38.0 - ($index * 0.5),
                'hombros' => 110.0 + ($index * 0.5),
                'pecho' => 95.0 + ($index * 0.5),
                'cintura' => $cinturas[$index],
                'cadera' => 98.0 - ($index * 0.5),
                'biceps' => 32.0 + ($index * 0.3),
                'antebrazos' => 28.0 + ($index * 0.2),
                'muslos' => 55.0 + ($index * 0.3),
                'pantorrillas' => 38.0 + ($index * 0.2)
            ]);
        }

        // Crear objetivos (histórico y actual)
        $objetivos = [
            [
                'objetivo_principal' => 'perdida_peso',
                'nivel_experiencia' => 'principiante',
                'dias_entrenamiento' => '3',
                'condiciones_medicas' => 'Ninguna',
                'activo' => false,
                'created_at' => Carbon::now()->subMonths(3)
            ],
            [
                'objetivo_principal' => 'tonificacion',
                'nivel_experiencia' => 'intermedio',
                'dias_entrenamiento' => '4',
                'condiciones_medicas' => 'Ninguna',
                'activo' => true,
                'created_at' => Carbon::now()->subMonth()
            ]
        ];

        foreach ($objetivos as $objetivo) {
            ObjetivoCliente::create([
                'cliente_id' => $cliente->id_cliente,
                'objetivo_principal' => $objetivo['objetivo_principal'],
                'nivel_experiencia' => $objetivo['nivel_experiencia'],
                'dias_entrenamiento' => $objetivo['dias_entrenamiento'],
                'condiciones_medicas' => $objetivo['condiciones_medicas'],
                'activo' => $objetivo['activo'],
                'created_at' => $objetivo['created_at'],
                'updated_at' => $objetivo['created_at']
            ]);
        }

        // CLIENTE BÁSICO
        // --------------
        $user2 = User::create([
            'name' => 'Cliente Básico',
            'email' => 'cliente.basico@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'rol' => 'cliente',
            'configuracion_completa' => true,
            'telefono' => '0912345678',
            'direccion' => 'Calle Secundaria 789, Ciudad',
            'foto_perfil' => null
        ]);
        
        $user2->assignRole('cliente');

        // Crear el cliente
        $cliente2 = Cliente::create([
            'user_id' => $user2->id,
            'gimnasio_id' => $gimnasio->id_gimnasio,
            'nombre' => 'Cliente Básico',
            'email' => 'cliente.basico@example.com',
            'fecha_nacimiento' => '1990-03-20',
            'telefono' => '0912345678',
            'genero' => 'F',
            'ocupacion' => 'Diseñadora'
        ]);

        // Crear membresía mensual
        $fechaCompra2 = Carbon::now()->subDays(15);
        $fechaVencimiento2 = $fechaCompra2->copy()->addDays($tipoMembresiaMensual->duracion_dias);
        
        $membresia2 = Membresia::create([
            'id_usuario' => $user2->id,
            'id_tipo_membresia' => $tipoMembresiaMensual->id_tipo_membresia,
            'precio_total' => $tipoMembresiaMensual->precio,
            'saldo_pendiente' => 0, // Pagado completamente
            'fecha_compra' => $fechaCompra2,
            'fecha_vencimiento' => $fechaVencimiento2,
            'renovacion' => true
        ]);

        // Crear pago para la membresía
        Pago::create([
            'id_membresia' => $membresia2->id_membresia,
            'id_usuario' => $user2->id,
            'monto' => $tipoMembresiaMensual->precio,
            'fecha_pago' => $fechaCompra2,
            'estado' => 'aprobado',
            'id_metodo_pago' => 1, // Efectivo
            'comprobante_url' => null,
            'notas' => 'Pago de membresía mensual',
            'fecha_aprobacion' => $fechaCompra2
        ]);

        // Crear asistencias (últimas 2 semanas, 2 veces por semana)
        $fechaInicio2 = Carbon::now()->subDays(15);
        $diasSemana2 = [2, 4]; // Martes, Jueves
        
        $fechaActual2 = $fechaInicio2->copy();
        while ($fechaActual2->lessThanOrEqualTo(Carbon::now())) {
            if (in_array($fechaActual2->dayOfWeek, $diasSemana2)) {
                $horaEntrada2 = $fechaActual2->copy()->setHour(rand(16, 19))->setMinute(rand(0, 59));
                $horaSalida2 = $horaEntrada2->copy()->addHours(rand(1, 2));
                
                // Verificar la estructura de la tabla asistencias
                $columnas = DB::getSchemaBuilder()->getColumnListing('asistencias');
                
                $asistenciaData2 = [
                    'fecha' => $fechaActual2->toDateString(),
                    'hora_entrada' => $horaEntrada2,
                    'hora_salida' => $horaSalida2,
                    'duracion_minutos' => $horaEntrada2->diffInMinutes($horaSalida2),
                    'estado' => 'activa',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                // Agregar campos según la estructura de la tabla
                if (in_array('cliente_id', $columnas)) {
                    $asistenciaData2['cliente_id'] = $cliente2->id_cliente;
                }
                
                if (in_array('id_cliente', $columnas)) {
                    $asistenciaData2['id_cliente'] = $cliente2->id_cliente;
                }
                
                if (in_array('gimnasio_id', $columnas)) {
                    $asistenciaData2['gimnasio_id'] = $gimnasio->id_gimnasio;
                }
                
                if (in_array('id_gimnasio', $columnas)) {
                    $asistenciaData2['id_gimnasio'] = $gimnasio->id_gimnasio;
                }
                
                DB::table('asistencias')->insert($asistenciaData2);
            }
            $fechaActual2->addDay();
        }

        // Crear medidas actuales
        MedidaCorporal::create([
            'cliente_id' => $cliente2->id_cliente,
            'fecha_medicion' => Carbon::now(),
            'peso' => 65.0,
            'altura' => 165.0,
            'cuello' => 32.0,
            'hombros' => 95.0,
            'pecho' => 88.0,
            'cintura' => 75.0,
            'cadera' => 92.0,
            'biceps' => 28.0,
            'antebrazos' => 24.0,
            'muslos' => 52.0,
            'pantorrillas' => 35.0
        ]);

        // Crear objetivo actual
        ObjetivoCliente::create([
            'cliente_id' => $cliente2->id_cliente,
            'objetivo_principal' => 'ganancia_muscular',
            'nivel_experiencia' => 'principiante',
            'dias_entrenamiento' => '3',
            'condiciones_medicas' => 'Ninguna',
            'activo' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
} 