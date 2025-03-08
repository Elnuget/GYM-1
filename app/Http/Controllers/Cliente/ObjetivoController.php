<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\ObjetivoCliente;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObjetivoController extends Controller
{
    public function index()
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        $objetivos = ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('cliente.perfil.objetivos', compact('objetivos'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'objetivo_principal' => 'required|in:perdida_peso,ganancia_muscular,mantenimiento,tonificacion,resistencia,flexibilidad',
            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
            'dias_entrenamiento' => 'required|string',
            'condiciones_medicas' => 'nullable|string',
        ]);
        
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
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
            'activo' => true,
        ]);
        
        return redirect()->route('cliente.perfil.objetivos')
            ->with('success', 'Objetivo registrado correctamente');
    }
} 