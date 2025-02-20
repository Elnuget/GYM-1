<?php

namespace App\Http\Controllers;

use App\Models\RutinaPredefinida;
use Illuminate\Http\Request;

class RutinaPredefinidaController extends Controller
{
    public function index()
    {
        $rutinas = RutinaPredefinida::with('entrenador')->paginate(10);
        return view('rutinas-predefinidas.index', compact('rutinas'));
    }

    public function create()
    {
        // Modificamos la verificación para incluir el rol 'admin'
        if (!auth()->check() || !in_array(auth()->user()->rol, ['admin', 'entrenador'])) {
            abort(403, 'No tienes permiso para crear rutinas.');
        }

        return view('rutinas-predefinidas.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->rol, ['admin', 'entrenador'])) {
            abort(403, 'No tienes permiso para crear rutinas.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|in:fuerza,resistencia,tonificacion,perdida_peso,ganancia_muscular,flexibilidad,rehabilitacion,mantenimiento',
            'estado' => 'required|in:activo,inactivo'
        ]);

        try {
            $validated['id_entrenador'] = auth()->id();
            $validated['fecha_creacion'] = now()->format('Y-m-d');

            $rutina = RutinaPredefinida::create($validated);

            return redirect()->route('rutinas-predefinidas.index')
                ->with('success', 'Rutina creada exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al crear rutina: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear la rutina: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(RutinaPredefinida $rutinaPredefinida)
    {
        // Permitir editar solo al creador o al admin
        if (auth()->user()->rol !== 'admin' && auth()->id() !== $rutinaPredefinida->id_entrenador) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        return view('rutinas-predefinidas.edit', compact('rutinaPredefinida'));
    }

    public function update(Request $request, RutinaPredefinida $rutinaPredefinida)
    {
        if (auth()->user()->rol !== 'admin' && auth()->id() !== $rutinaPredefinida->id_entrenador) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|in:fuerza,resistencia,tonificacion,perdida_peso,ganancia_muscular,flexibilidad,rehabilitacion,mantenimiento',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $rutinaPredefinida->update($validated);

        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina actualizada exitosamente');
    }

    public function destroy(RutinaPredefinida $rutinaPredefinida)
    {
        // Permitir eliminar solo al creador o al admin
        if (auth()->user()->rol !== 'admin' && auth()->id() !== $rutinaPredefinida->id_entrenador) {
            abort(403, 'No tienes permiso para eliminar esta rutina.');
        }

        $rutinaPredefinida->delete();
        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina eliminada exitosamente');
    }
} 