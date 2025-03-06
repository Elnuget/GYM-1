<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;

class MembresiaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cliente = Cliente::where('user_id', $user->id)->first();
        
        if (!$cliente) {
            return redirect()->route('completar.registro.cliente.form')
                ->with('error', 'Por favor, completa tu registro como cliente para ver tu membresía.');
        }

        $membresia = Membresia::with(['tipoMembresia.gimnasio'])
            ->where('id_usuario', $user->id)
            ->orderBy('fecha_vencimiento', 'desc')
            ->first();

        return view('cliente.membresia.index', compact('membresia'));
    }
} 