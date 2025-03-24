<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        // Configurar zona horaria para todas las instancias de Carbon en este controlador
        Carbon::setLocale('es');
        date_default_timezone_set('America/Guayaquil');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        // Inicializar la consulta base
        $query = Asistencia::with(['cliente.gimnasio'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc');
            
        // Verificar si el usuario es un dueño de gimnasio
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Filtrar asistencias que pertenecen a los gimnasios del dueño
            $query->whereHas('cliente', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            });
            
            // Filtrar clientes para mostrar solo los de los gimnasios del dueño
            $clientes = Cliente::whereIn('gimnasio_id', $gimnasiosIds)->get();
        } else {
            // Si no es dueño, mostrar todos los datos (para administradores)
            $clientes = Cliente::all();
        }

        // Verificar si se solicita mostrar todas las asistencias
        $mostrarTodas = $request->has('mostrar_todas');
        $fechaFiltro = $request->input('fecha', Carbon::today()->format('Y-m-d'));

        if (!$mostrarTodas) {
            $query->whereDate('fecha', $fechaFiltro);
        }

        $asistencias = $query->get();
        
        // Asegurarnos de que la duración y el estado se calculen correctamente
        foreach ($asistencias as $asistencia) {
            if ($asistencia->hora_salida && $asistencia->hora_entrada) {
                // Calcular la duración en minutos
                $entrada = Carbon::parse($asistencia->getRawOriginal('hora_entrada'));
                $salida = Carbon::parse($asistencia->getRawOriginal('hora_salida'));
                $asistencia->duracion_minutos = $salida->diffInMinutes($entrada);
                
                // Actualizar el estado si es necesario
                if ($entrada <= $salida && $asistencia->estado !== 'completada') {
                    $asistencia->estado = 'completada';
                    $asistencia->save();
                }
            }
        }
        
        return view('asistencias.index', compact('asistencias', 'clientes', 'mostrarTodas', 'fechaFiltro'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Datos recibidos en store: ', $request->all());

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
        ]);

        try {
            // Verificar si el usuario es un dueño de gimnasio
            $user = auth()->user();
            $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si el cliente pertenece a uno de los gimnasios del dueño
                $cliente = Cliente::find($request->cliente_id);
                $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
                
                if (!$cliente || !in_array($cliente->gimnasio_id, $gimnasiosIds->toArray())) {
                    return redirect()->route('asistencias.index')
                        ->with('error', 'No tienes permiso para registrar asistencias para este cliente.');
                }
            }

            // Determinar el estado inicial
            $estado = 'activa';
            if ($request->hora_salida) {
                $entrada = Carbon::parse($request->fecha . ' ' . $request->hora_entrada);
                $salida = Carbon::parse($request->fecha . ' ' . $request->hora_salida);
                if ($entrada <= $salida) {
                    $estado = 'completada';
                }
            }

            $asistencia = Asistencia::create([
                'cliente_id' => $request->cliente_id,
                'fecha' => $request->fecha,
                'hora_entrada' => $request->hora_entrada,
                'hora_salida' => $request->hora_salida,
                'notas' => $request->notas,
                'estado' => $estado,
            ]);

            Log::info('Asistencia creada: ', $asistencia->toArray());

            // Si se proporcionó hora de salida, calculamos la duración
            if ($request->hora_salida) {
                $asistencia->duracion_minutos = $asistencia->calcularDuracion();
                $asistencia->save();
            }

            return redirect()->route('asistencias.index')
                ->with('success', 'Asistencia registrada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear asistencia: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->route('asistencias.index')
                ->with('error', 'Error al registrar asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
        ]);

        // Verificar si el usuario es un dueño de gimnasio
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Verificar si la asistencia pertenece a un cliente de uno de los gimnasios del dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            $clientePertenece = Cliente::where('id_cliente', $request->cliente_id)
                ->whereIn('gimnasio_id', $gimnasiosIds)
                ->exists();
                
            if (!$clientePertenece) {
                return redirect()->route('asistencias.index')
                    ->with('error', 'No tienes permiso para modificar esta asistencia.');
            }
        }

        // Determinar el estado basado en la presencia de hora_salida
        $estado = $request->hora_salida ? 'completada' : 'activa';

        $asistencia->update([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'notas' => $request->notas,
            'estado' => $estado,
        ]);

        // Recalculamos la duración si hay hora de entrada y salida
        if ($asistencia->hora_entrada && $asistencia->hora_salida) {
            $asistencia->duracion_minutos = $asistencia->calcularDuracion();
            $asistencia->save();
        }

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asistencia $asistencia)
    {
        // Verificar si el usuario es un dueño de gimnasio
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Verificar si la asistencia pertenece a un cliente de uno de los gimnasios del dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            $clientePertenece = Cliente::where('id_cliente', $asistencia->cliente_id)
                ->whereIn('gimnasio_id', $gimnasiosIds)
                ->exists();
                
            if (!$clientePertenece) {
                return redirect()->route('asistencias.index')
                    ->with('error', 'No tienes permiso para eliminar esta asistencia.');
            }
        }

        $asistencia->delete();

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente.');
    }

    /**
     * Registrar la salida de una asistencia
     */
    public function registrarSalida(Asistencia $asistencia)
    {
        try {
            // Verificar si el usuario es un dueño de gimnasio
            $user = auth()->user();
            $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si la asistencia pertenece a un cliente de uno de los gimnasios del dueño
                $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
                $clientePertenece = Cliente::where('id_cliente', $asistencia->cliente_id)
                    ->whereIn('gimnasio_id', $gimnasiosIds)
                    ->exists();
                    
                if (!$clientePertenece) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para registrar la salida de esta asistencia.'
                    ], 403);
                }
            }

            if ($asistencia->hora_salida) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta asistencia ya tiene registrada la salida.'
                ], 400);
            }

            // Registrar la salida
            $asistencia->hora_salida = Carbon::now()->format('H:i:s');
            $asistencia->duracion_minutos = $asistencia->calcularDuracion();
            $asistencia->estado = 'completada';
            $asistencia->save();

            // Refrescar el modelo para asegurar que los datos estén actualizados
            $asistencia->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Salida registrada correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al registrar salida: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la salida: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método común para registrar entrada
    public function registrarEntrada(Request $request)
    {
        try {
            $cliente_id = $request->cliente_id;
            
            // Verificar si el usuario es un dueño de gimnasio
            $user = auth()->user();
            $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si el cliente pertenece a uno de los gimnasios del dueño
                $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
                $clientePertenece = Cliente::where('id_cliente', $cliente_id)
                    ->whereIn('gimnasio_id', $gimnasiosIds)
                    ->exists();
                    
                if (!$clientePertenece) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para registrar la entrada de este cliente.'
                    ], 403);
                }
            }
            
            // Verificar si ya existe una entrada sin salida
            $asistenciaActiva = Asistencia::where('cliente_id', $cliente_id)
                ->whereDate('fecha', Carbon::today())
                ->whereNull('hora_salida')
                ->first();

            if ($asistenciaActiva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una asistencia activa para este cliente hoy.'
                ], 400);
            }

            // Crear nueva asistencia con los campos exactos de la tabla
            $asistencia = new Asistencia();
            $asistencia->cliente_id = $cliente_id;
            $asistencia->fecha = Carbon::today();
            $asistencia->hora_entrada = Carbon::now()->format('H:i:s');
            $asistencia->estado = 'activa';
            $asistencia->save();

            return response()->json([
                'success' => true,
                'message' => 'Entrada registrada correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al registrar entrada: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la entrada: ' . $e->getMessage()
            ], 500);
        }
    }
}
