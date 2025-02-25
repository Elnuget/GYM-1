<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\RutinaCliente;
use App\Models\RutinaPredefinida;

class RutinaController extends Controller
{
    public function index()
    {
        $cliente = auth()->user()->cliente;
        
        $rutinaActual = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->with('rutina')
            ->first();

        $rutinasAnteriores = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', '!=', 'activa')
            ->with('rutina')
            ->latest()
            ->get();

        return view('cliente.rutinas.index', compact('rutinaActual', 'rutinasAnteriores'));
    }

    public function show(RutinaCliente $rutina)
    {
        $this->authorize('view', $rutina);
        
        $rutina->load('rutina');

        return view('cliente.rutinas.show', compact('rutina'));
    }

    public function actualizarProgreso(Request $request, RutinaCliente $rutina)
    {
        $this->authorize('update', $rutina);

        $request->validate([
            'progreso' => 'required|integer|min:0|max:100'
        ]);

        $rutina->update([
            'progreso' => $request->progreso
        ]);

        return back()->with('success', 'Progreso actualizado correctamente');
    }

    public function solicitarCambio(Request $request, RutinaCliente $rutina)
    {
        $this->authorize('update', $rutina);

        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        // Aquí podrías crear una notificación o solicitud para el entrenador
        
        return back()->with('success', 'Solicitud de cambio enviada correctamente');
    }

    public function actual()
    {
        $cliente = auth()->user()->cliente;
        $rutinaActual = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->with('rutina')
            ->first();

        return view('cliente.rutinas.actual', compact('rutinaActual'));
    }

    public function historial()
    {
        $cliente = auth()->user()->cliente;
        $rutinas = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', '!=', 'activa')
            ->with('rutina')
            ->latest()
            ->paginate(10);

        return view('cliente.rutinas.historial', compact('rutinas'));
    }

    public function ejercicios()
    {
        // Reutilizar la tabla rutinas_predefinidas para mostrar ejercicios
        $ejercicios = RutinaPredefinida::where('estado', 'activo')
            ->orderBy('nombre_rutina')
            ->get();

        return view('cliente.rutinas.ejercicios', compact('ejercicios'));
    }
} 