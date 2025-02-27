<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\Notificacion;
use App\Models\Anuncio;
use App\Models\User;
use App\Models\Cliente;
use Carbon\Carbon;

class ComunicacionController extends Controller
{
    public function index()
    {
        // Obtener el cliente con su relación de gimnasio
        $cliente = Cliente::where('user_id', auth()->id())->first();
        
        // Obtener mensajes con entrenadores
        $mensajes = Mensaje::where(function($query) {
                $query->where('emisor_id', auth()->id())
                      ->orWhere('receptor_id', auth()->id());
            })
            ->with(['emisor', 'receptor'])
            ->latest()
            ->take(10)
            ->get();

        // Obtener notificaciones no leídas
        $notificaciones = Notificacion::where('user_id', auth()->id())
            ->where('leida', false)
            ->latest()
            ->get();

        // Obtener anuncios activos del gimnasio si el cliente tiene un gimnasio asignado
        $anuncios = collect(); // Inicializar como colección vacía
        if ($cliente && $cliente->gimnasio_id) {
            $anuncios = Anuncio::where('gimnasio_id', $cliente->gimnasio_id)
                ->where('activo', true)
                ->latest('fecha_publicacion')
                ->get();
        }

        return view('cliente.comunicacion.index', compact('mensajes', 'notificaciones', 'anuncios'));
    }

    public function enviarMensaje(Request $request)
    {
        $request->validate([
            'receptor_id' => 'required|exists:users,id',
            'contenido' => 'required|string|max:1000'
        ]);

        Mensaje::create([
            'emisor_id' => auth()->id(),
            'receptor_id' => $request->receptor_id,
            'contenido' => $request->contenido
        ]);

        return back()->with('success', 'Mensaje enviado correctamente');
    }

    public function marcarNotificacionLeida(Notificacion $notificacion)
    {
        if ($notificacion->user_id !== auth()->id()) {
            abort(403);
        }

        $notificacion->update([
            'leida' => true,
            'fecha_lectura' => Carbon::now()
        ]);

        return response()->json(['success' => true]);
    }

    public function marcarMensajeLeido(Mensaje $mensaje)
    {
        if ($mensaje->receptor_id !== auth()->id()) {
            abort(403);
        }

        $mensaje->update([
            'leido' => true,
            'fecha_lectura' => Carbon::now()
        ]);

        return response()->json(['success' => true]);
    }
} 