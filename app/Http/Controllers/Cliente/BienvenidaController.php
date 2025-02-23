<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gimnasio;
use App\Models\Cliente;

class BienvenidaController extends Controller
{
    public function index()
    {
        // Obtener el cliente autenticado y su gimnasio
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        $gimnasio = Gimnasio::find($cliente->gimnasio_id);

        if (!$gimnasio) {
            // Si no se encuentra el gimnasio, redirigir con un mensaje
            return redirect()->route('dashboard')
                           ->with('error', 'No se encontró la información del gimnasio.');
        }

        // Asegurarse de que los campos opcionales tengan valores por defecto
        $gimnasio->horario = $gimnasio->horario ?? 'Horario no disponible';
        $gimnasio->email = $gimnasio->email ?? 'Email no disponible';
        $gimnasio->facebook = $gimnasio->facebook ?? '';
        $gimnasio->instagram = $gimnasio->instagram ?? '';

        return view('cliente.bienvenida', compact('gimnasio'));
    }
} 