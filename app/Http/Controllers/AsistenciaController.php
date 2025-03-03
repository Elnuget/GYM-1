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
        $asistencias = Asistencia::with('cliente')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(10);
        
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
            $asistencia = Asistencia::create([
                'cliente_id' => $request->cliente_id,
                'fecha' => $request->fecha,
                'hora_entrada' => $request->hora_entrada,
                'hora_salida' => $request->hora_salida,
                'notas' => $request->notas,
                'estado' => 'activa',
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

        $asistencia->update([
            'cliente_id' => $request->cliente_id,
            'fecha' => $request->fecha,
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'notas' => $request->notas,
            'estado' => $request->estado ?? 'activa',
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
     *
     * @param  \App\Models\Asistencia  $asistencia
     * @return \Illuminate\Http\Response
     */
    public function registrarSalida(Asistencia $asistencia)
    {
        $asistencia->registrarSalida(Carbon::now('America/Guayaquil')->format('H:i:s'));

        return redirect()->route('asistencias.index')
            ->with('success', 'Salida registrada correctamente.');
    }
}
