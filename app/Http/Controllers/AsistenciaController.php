<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('usuario')
            ->orderBy('fecha_asistencia', 'desc')
            ->orderBy('hora_ingreso', 'desc')
            ->paginate(10);
        $usuarios = User::where('rol', 'cliente')->get();
        
        return view('asistencias.index', compact('asistencias', 'usuarios'));
    }

    public function create()
    {
        $usuarios = User::where('rol', 'cliente')->get();
        return view('asistencias.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:users,id_usuario',
            'fecha_asistencia' => 'required|date',
            'hora_ingreso' => 'required|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i|after:hora_ingreso',
            'estado' => 'required|in:presente,ausente'
        ]);

        Asistencia::create($validated);

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia registrada exitosamente');
    }

    public function registrarEntrada(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:users,id_usuario'
        ]);

        $now = Carbon::now();

        Asistencia::create([
            'id_usuario' => $validated['id_usuario'],
            'fecha_asistencia' => $now->toDateString(),
            'hora_ingreso' => $now->toTimeString(),
            'estado' => 'presente'
        ]);

        return redirect()->route('asistencias.index')
            ->with('success', 'Entrada registrada exitosamente');
    }

    public function registrarSalida(Asistencia $asistencia)
    {
        if ($asistencia->hora_salida) {
            return redirect()->route('asistencias.index')
                ->with('error', 'La salida ya fue registrada');
        }

        $asistencia->update([
            'hora_salida' => Carbon::now()->toTimeString()
        ]);

        return redirect()->route('asistencias.index')
            ->with('success', 'Salida registrada exitosamente');
    }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        return redirect()->route('asistencias.index')
            ->with('success', 'Registro eliminado exitosamente');
    }
} 