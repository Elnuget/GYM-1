<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\RutinaCliente;
use App\Models\RutinaPredefinida;
use App\Models\Ejercicio;

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
        if ($rutina->cliente_id !== auth()->user()->cliente->id_cliente) {
            abort(403);
        }

        $rutina->load(['rutina' => function($query) {
            $query->with('ejercicios');
        }]);

        return view('cliente.rutinas.show', compact('rutina'));
    }

    public function actualizarProgreso(Request $request, RutinaCliente $rutina)
    {
        if ($rutina->cliente_id !== auth()->user()->cliente->id_cliente) {
            abort(403);
        }

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
        if ($rutina->cliente_id !== auth()->user()->cliente->id_cliente) {
            abort(403);
        }

        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        // Aquí podrías crear una notificación o solicitud para el entrenador
        
        return back()->with('success', 'Solicitud de cambio enviada correctamente');
    }

    public function actual()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $rutinaActual = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->with(['rutina' => function($query) {
                $query->with('ejercicios');
            }])
            ->first();

        return view('cliente.rutinas.actual', compact('rutinaActual'));
    }

    public function historial()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $rutinas = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', '!=', 'activa')
            ->with('rutina')
            ->latest()
            ->paginate(10);

        return view('cliente.rutinas.historial', compact('rutinas'));
    }

    public function ejercicios()
    {
        $ejercicios = Ejercicio::where('activo', true)
            ->orderBy('grupo_muscular')
            ->orderBy('nombre')
            ->get();

        return view('cliente.rutinas.ejercicios', compact('ejercicios'));
    }
} 