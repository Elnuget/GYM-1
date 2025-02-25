<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Cliente;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $asistenciaActual = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->whereDate('fecha', Carbon::today())
            ->first();

        $asistencias = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'completada')
            ->latest('fecha')
            ->paginate(10);

        return view('cliente.asistencias.index', compact('asistenciaActual', 'asistencias'));
    }

    public function registrarEntrada()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();

        // Verificar si ya existe una asistencia activa
        $asistenciaActiva = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->first();

        if ($asistenciaActiva) {
            return back()->with('error', 'Ya tienes una asistencia activa');
        }

        // Crear nueva asistencia
        Asistencia::create([
            'cliente_id' => $cliente->id_cliente,
            'fecha' => Carbon::today(),
            'hora_entrada' => Carbon::now(),
            'estado' => 'activa'
        ]);

        return back()->with('success', 'Entrada registrada correctamente');
    }

    public function registrarSalida(Asistencia $asistencia)
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();

        if ($asistencia->cliente_id !== $cliente->id_cliente) {
            abort(403);
        }

        if ($asistencia->estado !== 'activa') {
            return back()->with('error', 'Esta asistencia ya fue completada');
        }

        $hora_salida = Carbon::now();
        $duracion = Carbon::parse($asistencia->hora_entrada)->diffInMinutes($hora_salida);

        $asistencia->update([
            'hora_salida' => $hora_salida,
            'duracion_minutos' => $duracion,
            'estado' => 'completada'
        ]);

        return back()->with('success', 'Salida registrada correctamente');
    }
} 