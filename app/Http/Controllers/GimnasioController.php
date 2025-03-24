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
        $gimnasios = Gimnasio::with('dueno.user')->get();
        $duenos = DuenoGimnasio::with('user')->get();
        return view('gimnasios.index', compact('gimnasios', 'duenos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $duenos = DuenoGimnasio::with('user')->get();
        return view('gimnasios.create', compact('duenos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return view('gimnasios.show', compact('gimnasio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gimnasio $gimnasio)
    {
        $duenos = DuenoGimnasio::with('user')->get();
        return view('gimnasios.edit', compact('gimnasio', 'duenos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gimnasio $gimnasio)
    {
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
        $gimnasio->delete();

        return redirect()->route('gimnasios.index')
            ->with('success', 'Gimnasio eliminado exitosamente.');
    }
}
