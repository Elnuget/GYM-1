<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use Carbon\Carbon;

class MembresiaController extends Controller
{
    public function index()
    {
        // Obtener membresía activa del usuario actual
        $membresia = Membresia::where('id_usuario', auth()->id())
            ->where('fecha_vencimiento', '>=', Carbon::now())
            ->first();

        return view('cliente.membresia.index', compact('membresia'));
    }
} 