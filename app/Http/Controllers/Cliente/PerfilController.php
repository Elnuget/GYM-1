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
            'genero' => 'required|string|max:20',
            'ocupacion' => 'required|string|max:100',
            'direccion' => 'required|string|max:255',
        ]);

        $cliente->update($request->only([
            'fecha_nacimiento',
            'telefono',
            'genero',
            'ocupacion',
            'direccion'
        ]));

        return redirect()
            ->route('cliente.perfil.informacion')
            ->with('success', 'Información actualizada correctamente');
    }

    public function storeMedidas(Request $request)
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $request->validate([
            'fecha_medicion' => 'required|date',
            'peso' => 'required|numeric|min:0',
            'altura' => 'required|numeric|min:0',
            'cintura' => 'required|numeric|min:0',
            'pecho' => 'required|numeric|min:0',
            'biceps' => 'required|numeric|min:0',
            'muslos' => 'required|numeric|min:0',
            'pantorrillas' => 'required|numeric|min:0',
        ]);

        MedidaCorporal::create([
            'cliente_id' => $cliente->id_cliente,
            'fecha_medicion' => $request->fecha_medicion,
            'peso' => $request->peso,
            'altura' => $request->altura,
            'cintura' => $request->cintura,
            'pecho' => $request->pecho,
            'biceps' => $request->biceps,
            'muslos' => $request->muslos,
            'pantorrillas' => $request->pantorrillas,
        ]);

        return redirect()->route('cliente.perfil.medidas')
            ->with('success', 'Medidas registradas correctamente');
    }

    public function storeObjetivo(Request $request)
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $request->validate([
            'objetivo_principal' => 'required|string',
            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
            'dias_entrenamiento' => 'required|integer|min:1|max:7',
            'condiciones_medicas' => 'nullable|string',
        ]);

        // Desactivar objetivos anteriores
        ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
            ->where('activo', true)
            ->update(['activo' => false]);

        // Crear nuevo objetivo
        ObjetivoCliente::create([
            'cliente_id' => $cliente->id_cliente,
            'objetivo_principal' => $request->objetivo_principal,
            'nivel_experiencia' => $request->nivel_experiencia,
            'dias_entrenamiento' => $request->dias_entrenamiento,
            'condiciones_medicas' => $request->condiciones_medicas,
            'activo' => true
        ]);

        return redirect()->route('cliente.perfil.objetivos')
            ->with('success', 'Objetivo registrado correctamente');
    }
} 