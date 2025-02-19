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
        // También modificamos aquí la verificación
        if (!auth()->check() || !in_array(auth()->user()->rol, ['admin', 'entrenador'])) {
            abort(403, 'No tienes permiso para crear rutinas.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|string',
            'estado' => 'required|in:activa,inactiva'
        ]);

        // Agregamos el id del usuario que crea la rutina
        $validated['id_usuario'] = auth()->id();
        
        RutinaPredefinida::create($validated);

        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina creada exitosamente');
    }

    public function edit(RutinaPredefinida $rutinaPredefinida)
    {
        // Permitir editar solo al creador o al admin
        if (auth()->user()->rol !== 'admin' && auth()->id() !== $rutinaPredefinida->id_usuario) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        return view('rutinas-predefinidas.edit', compact('rutinaPredefinida'));
    }

    public function update(Request $request, RutinaPredefinida $rutinaPredefinida)
    {
        // Permitir editar solo al creador o al admin
        if (auth()->user()->rol !== 'admin' && auth()->id() !== $rutinaPredefinida->id_usuario) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|string',
            'estado' => 'required|in:activa,inactiva'
        ]);

        $rutinaPredefinida->update($validated);

        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina actualizada exitosamente');
    }

    public function destroy(RutinaPredefinida $rutinaPredefinida)
    {
        // Permitir eliminar solo al creador o al admin
        if (auth()->user()->rol !== 'admin' && auth()->id() !== $rutinaPredefinida->id_usuario) {
            abort(403, 'No tienes permiso para eliminar esta rutina.');
        }

        $rutinaPredefinida->delete();
        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina eliminada exitosamente');
    }
} 