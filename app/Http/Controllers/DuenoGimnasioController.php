<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DuenoGimnasioController extends Controller
{
    public function __construct()
    {
        // En Laravel 11, los middlewares se definen en las rutas
    }

    public function index()
    {
        $duenos = User::role('dueño')->get();
        return view('duenos-gimnasio.index', compact('duenos'));
    }

    public function create()
    {
        return view('duenos-gimnasio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255'
        ]);

        $dueno = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'rol' => 'dueño'
        ]);

        $dueno->assignRole('dueño');

        return redirect()->route('duenos-gimnasio.index')
            ->with('success', 'Dueño de gimnasio creado exitosamente');
    }

    public function edit(User $duenosGimnasio)
    {
        return view('duenos-gimnasio.edit', ['dueno' => $duenosGimnasio]);
    }

    public function update(Request $request, User $duenosGimnasio)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $duenosGimnasio->id,
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255'
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $duenosGimnasio->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $duenosGimnasio->update([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion
        ]);

        return redirect()->route('duenos-gimnasio.index')
            ->with('success', 'Dueño de gimnasio actualizado exitosamente');
    }

    public function destroy(User $duenosGimnasio)
    {
        $duenosGimnasio->delete();
        return redirect()->route('duenos-gimnasio.index')
            ->with('success', 'Dueño de gimnasio eliminado exitosamente');
    }
} 