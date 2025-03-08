<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\MedidaCorporal;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedidaController extends Controller
{
    public function index()
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        $medidas = MedidaCorporal::where('cliente_id', $cliente->id_cliente)
                    ->orderBy('fecha_medicion', 'desc')
                    ->get();
        
        return view('cliente.perfil.medidas', compact('medidas'));
    }
    
    public function store(Request $request)
    {
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
        
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
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
} 