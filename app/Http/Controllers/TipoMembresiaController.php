<?php

namespace App\Http\Controllers;

use App\Models\TipoMembresia;
use App\Models\Gimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoMembresiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposMembresia = TipoMembresia::with('gimnasio')->paginate(10);
        $gimnasios = Gimnasio::all();
        
        return view('tipos-membresia.index', compact('tiposMembresia', 'gimnasios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_dias' => 'required|integer|min:1',
            'tipo' => 'required|in:basica,estandar,premium',
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
        $request->validate([
            'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'duracion_dias' => 'required|integer|min:1',
            'tipo' => 'required|in:basica,estandar,premium',
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
        $tiposMembresia->delete();

        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Tipo de membresía eliminado correctamente');
    }

    /**
     * Cambiar el estado del tipo de membresía
     */
    public function cambiarEstado(TipoMembresia $tiposMembresia)
    {
        $tiposMembresia->estado = !$tiposMembresia->estado;
        $tiposMembresia->save();

        return redirect()->route('tipos-membresia.index')
            ->with('success', 'Estado del tipo de membresía actualizado correctamente');
    }
} 