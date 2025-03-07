<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\Notificacion;
use App\Models\Anuncio;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ComunicacionController extends Controller
{
    public function index()
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
        if (!$cliente) {
            return redirect()->route('cliente.dashboard')
                ->with('error', 'No se encontró el perfil de cliente.');
        }

        $mensajes = Mensaje::where('emisor_id', Auth::id())
            ->orWhere('receptor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $anuncios = Anuncio::where('gimnasio_id', $cliente->gimnasio_id)
            ->orderBy('fecha_publicacion', 'desc')
            ->get();

        return view('cliente.comunicacion.index', compact('mensajes', 'notificaciones', 'anuncios'));
    }

    public function enviarMensaje(Request $request)
    {
        $cliente = Cliente::where('user_id', Auth::id())->first();
        
        if (!$cliente) {
            return back()->with('error', 'No se encontró el perfil de cliente.');
        }

        $request->validate([
            'contenido' => 'required|string|max:1000'
        ]);

        $entrenador = Cliente::find($cliente->entrenador_id);
        if (!$entrenador) {
            return back()->with('error', 'No se encontró el entrenador asignado.');
        }

        Mensaje::create([
            'emisor_id' => Auth::id(),
            'receptor_id' => $entrenador->user_id,
            'contenido' => $request->contenido,
            'leido' => false
        ]);

        return back()->with('success', 'Mensaje enviado correctamente');
    }

    public function marcarNotificacionLeida($id)
    {
        $notificacion = Notificacion::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $notificacion->update(['leida' => true]);
        
        return response()->json(['success' => true]);
    }

    public function marcarMensajeLeido(Mensaje $mensaje)
    {
        if ($mensaje->receptor_id !== Auth::id()) {
            abort(403);
        }

        $mensaje->update([
            'leido' => true,
            'fecha_lectura' => Carbon::now()
        ]);

        return response()->json(['success' => true]);
    }
} 