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
    public function index()
    {
        // Obtener todas las asistencias para el dueño del gimnasio
        $asistencias = Asistencia::with('cliente')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(10);
        
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
        
        $clientes = Cliente::all();
        
        return view('asistencias.index', compact('asistencias', 'clientes'));
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
            if ($asistencia->hora_salida) {
                return back()->with('error', 'Esta asistencia ya tiene registrada la salida.');
            }

            // Registrar la salida
            $asistencia->hora_salida = Carbon::now()->format('H:i:s');
            $asistencia->duracion_minutos = $asistencia->calcularDuracion();
            $asistencia->estado = 'completada';
            $asistencia->save();

            // Refrescar el modelo para asegurar que los datos estén actualizados
            $asistencia->refresh();

            return back()->with('success', 'Salida registrada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }

    // Método común para registrar entrada
    public function registrarEntrada(Request $request)
    {
        try {
            $cliente_id = $request->cliente_id;
            
            // Verificar si ya existe una entrada sin salida
            $asistenciaActiva = Asistencia::where('cliente_id', $cliente_id)
                ->whereDate('fecha', Carbon::today())
                ->whereNull('hora_salida')
                ->first();

            if ($asistenciaActiva) {
                return back()->with('error', 'Ya existe una asistencia activa para este cliente hoy.');
            }

            // Crear nueva asistencia
            Asistencia::create([
                'cliente_id' => $cliente_id,
                'fecha' => Carbon::today(),
                'hora_entrada' => Carbon::now(),
                'estado' => 'activa'
            ]);

            return back()->with('success', 'Entrada registrada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar la entrada: ' . $e->getMessage());
        }
    }
}
