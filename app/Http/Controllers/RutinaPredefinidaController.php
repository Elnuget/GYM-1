<?php

namespace App\Http\Controllers;

use App\Models\RutinaPredefinida;
use App\Models\Gimnasio;
use App\Models\DuenoGimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RutinaPredefinidaController extends Controller
{
    public function index()
    {
        $rutinas = RutinaPredefinida::with('gimnasio')->paginate(10);
        
        // Obtener los gimnasios disponibles según el rol del usuario
        if (Auth::user()->rol === 'admin') {
            $gimnasios = Gimnasio::all();
        } elseif (Auth::user()->rol === 'dueño') {
            // Obtener el registro de DueñoGimnasio asociado al usuario
            $duenoGimnasio = DuenoGimnasio::where('user_id', Auth::id())->first();
            
            // Si existe, obtener sus gimnasios
            if ($duenoGimnasio) {
                $gimnasios = $duenoGimnasio->gimnasios;
            } else {
                $gimnasios = collect(); // Colección vacía si no hay gimnasios
            }
        } elseif (Auth::user()->rol === 'entrenador') {
            // Aquí deberías tener una lógica para obtener los gimnasios donde trabaja el entrenador
            // Por ahora, como ejemplo, mostraré todos los gimnasios
            $gimnasios = Gimnasio::all();
        } else {
            $gimnasios = collect(); // Colección vacía para otros roles
        }
        
        return view('rutinas-predefinidas.index', compact('rutinas', 'gimnasios'));
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
        } elseif (Auth::user()->rol === 'dueño') {
            // Obtener el registro de DueñoGimnasio asociado al usuario
            $duenoGimnasio = DuenoGimnasio::where('user_id', Auth::id())->first();
            
            // Si existe, obtener sus gimnasios
            if ($duenoGimnasio) {
                $gimnasios = $duenoGimnasio->gimnasios;
            } else {
                $gimnasios = collect(); // Colección vacía si no hay gimnasios
            }
        } elseif (Auth::user()->rol === 'entrenador') {
            // Aquí deberías tener una lógica para obtener los gimnasios donde trabaja el entrenador
            // Por ahora, como ejemplo, mostraré todos los gimnasios
            $gimnasios = Gimnasio::all();
        } else {
            $gimnasios = collect(); // Colección vacía para otros roles
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
            'activo' => 'required|boolean',
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
        // Verificar permisos según el rol del usuario
        if (Auth::user()->rol === 'admin') {
            // Administrador puede editar cualquier rutina
            $permitido = true;
        } elseif (Auth::user()->rol === 'dueño') {
            // Verificar si la rutina pertenece a un gimnasio del dueño
            $duenoGimnasio = DuenoGimnasio::where('user_id', Auth::id())->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio')->toArray();
                $permitido = in_array($rutinaPredefinida->gimnasio_id, $gimnasiosIds);
            } else {
                $permitido = false;
            }
        } elseif (Auth::user()->rol === 'entrenador') {
            // Aquí deberías tener una lógica para verificar si el entrenador pertenece al gimnasio
            // Por ahora, como ejemplo, permitiremos a todos los entrenadores
            $permitido = true;
        } else {
            $permitido = false;
        }
        
        if (!$permitido) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        // Obtener gimnasios según el rol
        if (Auth::user()->rol === 'admin') {
            $gimnasios = Gimnasio::all();
        } elseif (Auth::user()->rol === 'dueño') {
            $duenoGimnasio = DuenoGimnasio::where('user_id', Auth::id())->first();
            if ($duenoGimnasio) {
                $gimnasios = $duenoGimnasio->gimnasios;
            } else {
                $gimnasios = collect();
            }
        } elseif (Auth::user()->rol === 'entrenador') {
            // Obtener gimnasios donde trabaja el entrenador
            $gimnasios = Gimnasio::all(); // Por ahora todos
        } else {
            $gimnasios = collect();
        }

        return view('rutinas-predefinidas.edit', compact('rutinaPredefinida', 'gimnasios'));
    }

    public function update(Request $request, RutinaPredefinida $rutinaPredefinida)
    {
        // Verificar permisos según el rol del usuario
        if (Auth::user()->rol === 'admin') {
            // Administrador puede editar cualquier rutina
            $permitido = true;
        } elseif (Auth::user()->rol === 'dueño') {
            // Verificar si la rutina pertenece a un gimnasio del dueño
            $duenoGimnasio = DuenoGimnasio::where('user_id', Auth::id())->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio')->toArray();
                $permitido = in_array($rutinaPredefinida->gimnasio_id, $gimnasiosIds);
            } else {
                $permitido = false;
            }
        } elseif (Auth::user()->rol === 'entrenador') {
            // Aquí deberías tener una lógica para verificar si el entrenador pertenece al gimnasio
            // Por ahora, como ejemplo, permitiremos a todos los entrenadores
            $permitido = true;
        } else {
            $permitido = false;
        }
        
        if (!$permitido) {
            abort(403, 'No tienes permiso para editar esta rutina.');
        }

        $validated = $request->validate([
            'nombre_rutina' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'objetivo' => 'required|in:fuerza,resistencia,tonificacion,perdida_peso,ganancia_muscular,flexibilidad,rehabilitacion,mantenimiento',
            'activo' => 'required|boolean',
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio'
        ]);

        $rutinaPredefinida->update($validated);

        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina actualizada exitosamente');
    }

    public function destroy(RutinaPredefinida $rutinaPredefinida)
    {
        // Verificar permisos según el rol del usuario
        if (Auth::user()->rol === 'admin') {
            // Administrador puede eliminar cualquier rutina
            $permitido = true;
        } elseif (Auth::user()->rol === 'dueño') {
            // Verificar si la rutina pertenece a un gimnasio del dueño
            $duenoGimnasio = DuenoGimnasio::where('user_id', Auth::id())->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio')->toArray();
                $permitido = in_array($rutinaPredefinida->gimnasio_id, $gimnasiosIds);
            } else {
                $permitido = false;
            }
        } elseif (Auth::user()->rol === 'entrenador') {
            // Aquí deberías tener una lógica para verificar si el entrenador pertenece al gimnasio
            // Por ahora, como ejemplo, permitiremos a todos los entrenadores
            $permitido = true;
        } else {
            $permitido = false;
        }
        
        if (!$permitido) {
            abort(403, 'No tienes permiso para eliminar esta rutina.');
        }

        $rutinaPredefinida->delete();
        return redirect()->route('rutinas-predefinidas.index')
            ->with('success', 'Rutina eliminada exitosamente');
    }
} 