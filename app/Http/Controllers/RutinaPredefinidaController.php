<?php

namespace App\Http\Controllers;

use App\Models\RutinaPredefinida;
use App\Models\Gimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RutinaPredefinidaController extends Controller
{
    public function index()
    {
        $rutinas = RutinaPredefinida::with('gimnasio')->paginate(10);
        return view('rutinas-predefinidas.index', compact('rutinas'));
    }

    public function create()
    {
        // Modificamos la verificación para incluir los roles 'admin', 'entrenador' y 'dueño'
        if (!Auth::check() || !in_array(Auth::user()->rol, ['admin', 'entrenador', 'dueño'])) {
            abort(403, 'No tienes permiso para crear rutinas.');
        }

        // Obtener los gimnasios disponibles según el rol del usuario
        if (Auth::user()->rol === 'admin') {
            $gimnasios = Gimnasio::all();
        } else {
            // Si es dueño o entrenador, obtener solo sus gimnasios asociados
            $gimnasios = Auth::user()->gimnasios;
        }

        return view('rutinas-predefinidas.create', compact('gimnasios'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->rol, ['admin', 'entrenador', 'dueño'])) {
            abort(403, 'No tienes permiso para crear rutinas.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|in:fuerza,resistencia,tonificacion,perdida_peso,ganancia_muscular,flexibilidad,rehabilitacion,mantenimiento',
            'estado' => 'required|in:activo,inactivo',
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio'
        ]);

        try {
            $validated['fecha_creacion'] = now()->format('Y-m-d');

            $rutina = RutinaPredefinida::create($validated);

            return redirect()->route('rutinas-predefinidas.index')
                ->with('success', 'Rutina creada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear rutina: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear la rutina: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(RutinaPredefinida $rutinaPredefinida)
    {
        // Verificar permisos - si es admin puede editar cualquiera,
        // o si pertenece al mismo gimnasio del usuario
        $userGimnasios = Auth::user()->gimnasios->pluck('id_gimnasio')->toArray();
        
        if (Auth::user()->rol !== 'admin' && !in_array($rutinaPredefinida->gimnasio_id, $userGimnasios)) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        // Obtener gimnasios según el rol
        if (Auth::user()->rol === 'admin') {
            $gimnasios = Gimnasio::all();
        } else {
            $gimnasios = Auth::user()->gimnasios;
        }

        return view('rutinas-predefinidas.edit', compact('rutinaPredefinida', 'gimnasios'));
    }

    public function update(Request $request, RutinaPredefinida $rutinaPredefinida)
    {
        // Verificar permisos
        $userGimnasios = Auth::user()->gimnasios->pluck('id_gimnasio')->toArray();
        
        if (Auth::user()->rol !== 'admin' && !in_array($rutinaPredefinida->gimnasio_id, $userGimnasios)) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|in:fuerza,resistencia,tonificacion,perdida_peso,ganancia_muscular,flexibilidad,rehabilitacion,mantenimiento',
            'estado' => 'required|in:activo,inactivo',
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio'
        ]);

        $rutinaPredefinida->update($validated);

        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina actualizada exitosamente');
    }

    public function destroy(RutinaPredefinida $rutinaPredefinida)
    {
        // Verificar permisos
        $userGimnasios = Auth::user()->gimnasios->pluck('id_gimnasio')->toArray();
        
        if (Auth::user()->rol !== 'admin' && !in_array($rutinaPredefinida->gimnasio_id, $userGimnasios)) {
            abort(403, 'No tienes permiso para eliminar esta rutina.');
        }

        $rutinaPredefinida->delete();
        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina eliminada exitosamente');
    }
} 