<?php

namespace App\Http\Controllers;

use App\Models\Gimnasio;
use App\Models\User;
use App\Models\DuenoGimnasio;
use Illuminate\Http\Request;

class GimnasioController extends Controller
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
            // Si es dueño, mostrar solo sus gimnasios
            $gimnasios = Gimnasio::with('dueno.user')
                ->where('dueno_id', $dueno->id_dueno)
                ->get();
            
            // Solo necesita ver su propio registro de dueño
            $duenos = DuenoGimnasio::with('user')
                ->where('id_dueno', $dueno->id_dueno)
                ->get();
        } else {
            // Si es admin, mostrar todos los gimnasios
            $gimnasios = Gimnasio::with('dueno.user')->get();
            $duenos = DuenoGimnasio::with('user')->get();
        }

        return view('gimnasios.index', compact('gimnasios', 'duenos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verificar si el usuario es un dueño
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Si es dueño, solo puede seleccionarse a sí mismo
            $duenos = DuenoGimnasio::with('user')
                ->where('id_dueno', $dueno->id_dueno)
                ->get();
        } else {
            // Si es admin, puede seleccionar cualquier dueño
            $duenos = DuenoGimnasio::with('user')->get();
        }
        
        return view('gimnasios.create', compact('duenos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si el usuario es un dueño
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno && $request->dueno_id != $dueno->id_dueno) {
            // Si es dueño, solo puede crear gimnasios para sí mismo
            return redirect()->route('gimnasios.index')
                ->with('error', 'No tienes permiso para crear gimnasios para otros dueños.');
        }

        $validated = $request->validate([
            'dueno_id' => 'required|exists:duenos_gimnasios,id_dueno',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        Gimnasio::create($validated);

        return redirect()->route('gimnasios.index')
            ->with('success', 'Gimnasio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gimnasio $gimnasio)
    {
        // Verificar si el usuario es un dueño y tiene permiso para ver este gimnasio
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno && $gimnasio->dueno_id != $dueno->id_dueno) {
            return redirect()->route('gimnasios.index')
                ->with('error', 'No tienes permiso para ver este gimnasio.');
        }
        
        return view('gimnasios.show', compact('gimnasio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gimnasio $gimnasio)
    {
        // Verificar si el usuario es un dueño y tiene permiso para editar este gimnasio
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno && $gimnasio->dueno_id != $dueno->id_dueno) {
            return redirect()->route('gimnasios.index')
                ->with('error', 'No tienes permiso para editar este gimnasio.');
        }
        
        if ($dueno) {
            // Si es dueño, solo puede seleccionarse a sí mismo
            $duenos = DuenoGimnasio::with('user')
                ->where('id_dueno', $dueno->id_dueno)
                ->get();
        } else {
            // Si es admin, puede seleccionar cualquier dueño
            $duenos = DuenoGimnasio::with('user')->get();
        }
        
        return view('gimnasios.edit', compact('gimnasio', 'duenos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gimnasio $gimnasio)
    {
        // Verificar si el usuario es un dueño y tiene permiso para actualizar este gimnasio
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Si es dueño, verificar que el gimnasio le pertenece
            if ($gimnasio->dueno_id != $dueno->id_dueno) {
                return redirect()->route('gimnasios.index')
                    ->with('error', 'No tienes permiso para editar este gimnasio.');
            }
            
            // Además, no puede cambiar el dueño a otro que no sea él mismo
            if ($request->dueno_id != $dueno->id_dueno) {
                return redirect()->route('gimnasios.index')
                    ->with('error', 'No puedes transferir el gimnasio a otro dueño.');
            }
        }

        $validated = $request->validate([
            'dueno_id' => 'required|exists:duenos_gimnasios,id_dueno',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $gimnasio->update($validated);

        return redirect()->route('gimnasios.index')
            ->with('success', 'Gimnasio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gimnasio $gimnasio)
    {
        // Verificar si el usuario es un dueño y tiene permiso para eliminar este gimnasio
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno && $gimnasio->dueno_id != $dueno->id_dueno) {
            return redirect()->route('gimnasios.index')
                ->with('error', 'No tienes permiso para eliminar este gimnasio.');
        }
        
        $gimnasio->delete();

        return redirect()->route('gimnasios.index')
            ->with('success', 'Gimnasio eliminado exitosamente.');
    }
}
