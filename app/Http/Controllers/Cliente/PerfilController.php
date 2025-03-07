<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Medida;
use App\Models\Objetivo;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        return view('cliente.perfil.index');
    }

    public function informacion()
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        return view('cliente.perfil.informacion', compact('cliente'));
    }

    public function medidas()
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        $medidas = Medida::where('cliente_id', $cliente->id_cliente)
            ->orderBy('fecha_medicion', 'desc')
            ->get();
        return view('cliente.perfil.medidas', compact('medidas'));
    }

    public function objetivos()
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        $objetivos = Objetivo::where('cliente_id', $cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('cliente.perfil.objetivos', compact('objetivos'));
    }

    public function actualizar(Request $request)
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
        $request->validate([
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|in:M,F,O',
            'ocupacion' => 'nullable|string|max:100',
        ]);

        $cliente->update($request->all());

        return back()->with('success', 'Información actualizada correctamente');
    }

    public function guardarMedidas(Request $request)
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
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

        Medida::create([
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

        return back()->with('success', 'Medidas guardadas correctamente');
    }

    public function guardarObjetivos(Request $request)
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
        $request->validate([
            'objetivo_principal' => 'required|string',
            'nivel_experiencia' => 'required|string',
            'dias_entrenamiento' => 'required|integer|min:1|max:7',
            'condiciones_medicas' => 'nullable|string',
        ]);

        Objetivo::create([
            'cliente_id' => $cliente->id_cliente,
            'objetivo_principal' => $request->objetivo_principal,
            'nivel_experiencia' => $request->nivel_experiencia,
            'dias_entrenamiento' => $request->dias_entrenamiento,
            'condiciones_medicas' => $request->condiciones_medicas,
            'activo' => true,
        ]);

        return back()->with('success', 'Objetivo guardado correctamente');
    }
} 