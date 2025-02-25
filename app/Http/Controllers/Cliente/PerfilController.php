<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\MedidaCorporal;
use App\Models\ObjetivoCliente;

class PerfilController extends Controller
{
    public function index()
    {
        return view('cliente.perfil.index');
    }

    public function informacion()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        return view('cliente.perfil.informacion', compact('cliente'));
    }

    public function medidas()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        $medidas = MedidaCorporal::where('cliente_id', $cliente->id_cliente)
            ->orderBy('fecha_medicion', 'desc')
            ->get();

        return view('cliente.perfil.medidas', compact('medidas'));
    }

    public function objetivos()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        $objetivos = ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
            ->where('activo', true)
            ->get();

        return view('cliente.perfil.objetivos', compact('objetivos'));
    }

    public function actualizar(Request $request)
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $request->validate([
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'genero' => 'required|in:M,F,O',
            'ocupacion' => 'required|string|max:100',
        ]);

        $cliente->update($request->only([
            'fecha_nacimiento',
            'telefono',
            'genero',
            'ocupacion'
        ]));

        return redirect()
            ->route('cliente.perfil.informacion')
            ->with('success', 'Información actualizada correctamente');
    }
} 