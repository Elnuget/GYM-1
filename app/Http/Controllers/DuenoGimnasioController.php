<?php

namespace App\Http\Controllers;

use App\Models\DuenoGimnasio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DuenoGimnasioController extends Controller
{
    public function index()
    {
        $duenosGimnasio = DuenoGimnasio::with('user')->paginate(10);
        return view('duenos-gimnasio.index', compact('duenosGimnasio'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'nombre_comercial' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
            ]);

            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Crear dueño de gimnasio
            $duenoGimnasio = DuenoGimnasio::create([
                'user_id' => $user->id,
                'nombre_comercial' => $request->nombre_comercial,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dueño de gimnasio creado exitosamente',
                'data' => $duenoGimnasio
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el dueño de gimnasio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $duenoGimnasio = DuenoGimnasio::with('user')->findOrFail($id);
        return response()->json($duenoGimnasio);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $duenoGimnasio = DuenoGimnasio::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $duenoGimnasio->user_id,
                'nombre_comercial' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
            ]);

            // Actualizar usuario
            $duenoGimnasio->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Actualizar dueño de gimnasio
            $duenoGimnasio->update([
                'nombre_comercial' => $request->nombre_comercial,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dueño de gimnasio actualizado exitosamente',
                'data' => $duenoGimnasio
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el dueño de gimnasio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $duenoGimnasio = DuenoGimnasio::findOrFail($id);
            $user = $duenoGimnasio->user;

            DB::beginTransaction();
            
            $duenoGimnasio->delete();
            $user->delete();
            
            DB::commit();

            return redirect()->route('duenos-gimnasio.index')
                ->with('success', 'Dueño de gimnasio eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('duenos-gimnasio.index')
                ->with('error', 'Error al eliminar el dueño de gimnasio: ' . $e->getMessage());
        }
    }
} 