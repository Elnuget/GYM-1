<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\PlanNutricional;

class NutricionController extends Controller
{
    public function index()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        return view('cliente.nutricion.index', compact('cliente'));
    }

    public function planActual()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        $planActual = PlanNutricional::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activo')
            ->first();

        return view('cliente.nutricion.plan-actual', compact('planActual'));
    }

    public function historial()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        $planes = PlanNutricional::where('cliente_id', $cliente->id_cliente)
            ->where('estado', '!=', 'activo')
            ->latest()
            ->paginate(10);

        return view('cliente.nutricion.historial', compact('planes'));
    }
} 