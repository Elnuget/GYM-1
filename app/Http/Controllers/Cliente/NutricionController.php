<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Nutricion;
use App\Models\ComidaNutricion;

class NutricionController extends Controller
{
    public function actual()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $planActual = Nutricion::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activo')
            ->with('comidas')
            ->first();

        return view('cliente.nutricion.actual', compact('planActual'));
    }

    public function historial()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $planes = Nutricion::where('cliente_id', $cliente->id_cliente)
            ->where('estado', '!=', 'activo')
            ->latest()
            ->paginate(10);

        return view('cliente.nutricion.historial', compact('planes'));
    }

    public function show(Nutricion $nutricion)
    {
        if ($nutricion->cliente_id !== auth()->user()->cliente->id_cliente) {
            abort(403);
        }

        $nutricion->load('comidas');

        return view('cliente.nutricion.show', compact('nutricion'));
    }

    public function registrarComida(Request $request, Nutricion $nutricion)
    {
        if ($nutricion->cliente_id !== auth()->user()->cliente->id_cliente) {
            abort(403);
        }

        $request->validate([
            'comida_id' => 'required|exists:comidas_nutricion,id',
            'completada' => 'required|boolean',
            'notas' => 'nullable|string|max:500'
        ]);

        // Aquí iría la lógica para registrar el seguimiento de las comidas
        // Podrías crear una tabla de seguimiento si lo necesitas

        return back()->with('success', 'Comida registrada correctamente');
    }

    public function solicitarCambio(Request $request, Nutricion $nutricion)
    {
        if ($nutricion->cliente_id !== auth()->user()->cliente->id_cliente) {
            abort(403);
        }

        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        // Aquí podrías crear una notificación o solicitud para el nutricionista
        
        return back()->with('success', 'Solicitud de cambio enviada correctamente');
    }
} 