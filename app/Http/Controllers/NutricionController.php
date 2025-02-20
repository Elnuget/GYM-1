<?php

namespace App\Http\Controllers;

use App\Models\Nutricion;
use App\Models\User;
use Illuminate\Http\Request;

class NutricionController extends Controller
{
    public function index()
    {
        $registros = Nutricion::with('usuario')
            ->orderBy('fecha_asignacion', 'desc')
            ->paginate(10);
        $usuarios = User::where('rol', 'cliente')->get();
        return view('nutricion.index', compact('registros', 'usuarios'));
    }

    public function create()
    {
        $usuarios = User::where('rol', 'cliente')->get();
        return view('nutricion.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:users,id_usuario',
            'informacion' => 'required|string',
            'plan_dieta' => 'nullable|string',
            'fecha_asignacion' => 'required|date'
        ]);

        Nutricion::create($validated);

        return redirect()->route('nutricion.index')
            ->with('success', 'Información nutricional registrada exitosamente');
    }

    public function edit(Nutricion $nutricion)
    {
        $usuarios = User::where('rol', 'cliente')->get();
        return view('nutricion.edit', compact('nutricion', 'usuarios'));
    }

    public function update(Request $request, Nutricion $nutricion)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:users,id_usuario',
            'informacion' => 'required|string',
            'plan_dieta' => 'nullable|string',
            'fecha_asignacion' => 'required|date'
        ]);

        $nutricion->update($validated);

        return redirect()->route('nutricion.index')
            ->with('success', 'Información nutricional actualizada exitosamente');
    }

    public function destroy(Nutricion $nutricion)
    {
        $nutricion->delete();
        return redirect()->route('nutricion.index')
            ->with('success', 'Registro eliminado exitosamente');
    }
} 