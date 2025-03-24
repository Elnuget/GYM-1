<?php

namespace App\Http\Controllers;

use App\Models\TipoMembresia;
use App\Models\Gimnasio;
use App\Models\DuenoGimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoMembresiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        
        // Verificar si el usuario es un dueño de gimnasio
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Si es dueño, mostrar solo tipos de membresía de sus gimnasios
            $gimnasiosIds = Gimnasio::where('dueno_id', $dueno->id_dueno)->pluck('id_gimnasio');
            
            $tiposMembresia = TipoMembresia::with('gimnasio')
                ->whereIn('gimnasio_id', $gimnasiosIds)
                ->paginate(10);
                
            $gimnasios = Gimnasio::whereIn('id_gimnasio', $gimnasiosIds)->get();
        } else {
            // Si es admin, mostrar todos los tipos de membresía
            $tiposMembresia = TipoMembresia::with('gimnasio')->paginate(10);
            $gimnasios = Gimnasio::all();
        }
        
        return view('tipos-membresia.index', compact('tiposMembresia', 'gimnasios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si el usuario es un dueño
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Verificar si el gimnasio pertenece al dueño
            $gimnasio = Gimnasio::where('id_gimnasio', $request->gimnasio_id)
                ->where('dueno_id', $dueno->id_dueno)
                ->first();
                
            if (!$gimnasio) {
                return redirect()->route('tipos-membresia.index')
                    ->with('error', 'No tienes permiso para crear tipos de membresía para este gimnasio.');
            }
        }
        
        $request->validate([
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_dias' => 'nullable|integer|min:1',
            'tipo' => 'required|in:mensual,anual,visitas',
            'numero_visitas' => 'nullable|integer|min:1|required_if:tipo,visitas',
        ]);

        TipoMembresia::create($request->all());

        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía creado correctamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoMembresia $tiposMembresia)
    {
        // Verificar si el usuario es un dueño
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Verificar si el tipo de membresía pertenece a un gimnasio del dueño
            $gimnasioPertenece = Gimnasio::where('id_gimnasio', $tiposMembresia->gimnasio_id)
                ->where('dueno_id', $dueno->id_dueno)
                ->exists();
                
            if (!$gimnasioPertenece) {
                return redirect()->route('tipos-membresia.index')
                    ->with('error', 'No tienes permiso para editar este tipo de membresía.');
            }
            
            // Verificar si el nuevo gimnasio pertenece al dueño
            $nuevoGimnasioPertenece = Gimnasio::where('id_gimnasio', $request->gimnasio_id)
                ->where('dueno_id', $dueno->id_dueno)
                ->exists();
                
            if (!$nuevoGimnasioPertenece) {
                return redirect()->route('tipos-membresia.index')
                    ->with('error', 'No puedes transferir este tipo de membresía a un gimnasio que no te pertenece.');
            }
        }
        
        $request->validate([
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_dias' => 'nullable|integer|min:1',
            'tipo' => 'required|in:mensual,anual,visitas',
            'numero_visitas' => 'nullable|integer|min:1|required_if:tipo,visitas',
        ]);

        $tiposMembresia->update($request->all());
        
        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoMembresia $tiposMembresia)
    {
        // Verificar si el usuario es un dueño
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Verificar si el tipo de membresía pertenece a un gimnasio del dueño
            $gimnasioPertenece = Gimnasio::where('id_gimnasio', $tiposMembresia->gimnasio_id)
                ->where('dueno_id', $dueno->id_dueno)
                ->exists();
                
            if (!$gimnasioPertenece) {
                return redirect()->route('tipos-membresia.index')
                    ->with('error', 'No tienes permiso para eliminar este tipo de membresía.');
            }
        }
        
        $tiposMembresia->delete();
        
        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía eliminado correctamente');
    }

    /**
     * Change the status of the specified resource.
     */
    public function cambiarEstado(TipoMembresia $tiposMembresia)
    {
        // Verificar si el usuario es un dueño
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Verificar si el tipo de membresía pertenece a un gimnasio del dueño
            $gimnasioPertenece = Gimnasio::where('id_gimnasio', $tiposMembresia->gimnasio_id)
                ->where('dueno_id', $dueno->id_dueno)
                ->exists();
                
            if (!$gimnasioPertenece) {
                return redirect()->route('tipos-membresia.index')
                    ->with('error', 'No tienes permiso para cambiar el estado de este tipo de membresía.');
            }
        }
        
        $tiposMembresia->estado = !$tiposMembresia->estado;
        $tiposMembresia->save();
        
        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Estado del tipo de membresía actualizado correctamente');
    }
} 