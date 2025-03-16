<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Gimnasio;
use App\Models\Membresia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::with('gimnasio')->get();
        $gimnasios = Gimnasio::all();
        
        // Cargar todas las membresías con sus relaciones necesarias
        $todasLasMembresias = Membresia::with(['usuario', 'tipoMembresia'])->get();
        
        // Cargar todos los pagos con sus relaciones
        $todosLosPagos = \App\Models\Pago::with(['usuario', 'membresia.tipoMembresia', 'metodoPago'])->get();
        
        // Cargar todas las asistencias con sus relaciones
        $todasLasAsistencias = \App\Models\Asistencia::with('cliente')->get();
        
        return view('clientes.index', compact('clientes', 'gimnasios', 'todasLasMembresias', 'todosLosPagos', 'todasLasAsistencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gimnasios = Gimnasio::all();
        return view('clientes.create', compact('gimnasios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'gimnasio_id' => ['required', 'exists:gimnasios,id_gimnasio'],
                'nombre' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'telefono' => ['nullable', 'string', 'max:20'],
                'fecha_nacimiento' => ['nullable', 'date'],
                'genero' => ['nullable', 'string', 'in:masculino,femenino,otro'],
                'direccion' => ['nullable', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]);

            DB::beginTransaction();

            // Crear el usuario
            $user = User::create([
                'name' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'cliente'
            ]);

            // Asignar rol
            $user->assignRole('cliente');

            // Crear el cliente
            $cliente = new Cliente();
            $cliente->user_id = $user->id;
            $cliente->gimnasio_id = $request->gimnasio_id;
            $cliente->nombre = $request->nombre;
            $cliente->email = $request->email;
            $cliente->telefono = $request->telefono;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->genero = $request->genero;
            $cliente->direccion = $request->direccion;
            $cliente->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Cliente {$cliente->nombre} creado exitosamente.\nSe ha creado una cuenta con el email: {$cliente->email}\nContraseña: gymflow2025"
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        $gimnasios = Gimnasio::all();
        return view('clientes.edit', compact('cliente', 'gimnasios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        try {
            $validated = $request->validate([
                'gimnasio_id' => 'required|exists:gimnasios,id_gimnasio',
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:clientes,email,' . $cliente->id_cliente . ',id_cliente',
                'telefono' => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'genero' => 'nullable|string|in:masculino,femenino,otro',
                'direccion' => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            // Actualizar el cliente
            $cliente->update($validated);

            // Si existe un usuario asociado, actualizar datos básicos
            if ($cliente->user_id) {
                $user = User::find($cliente->user_id);
                if ($user) {
                    $user->name = $request->nombre;
                    $user->email = $request->email;
                    $user->save();
                }
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Cliente {$cliente->nombre} actualizado exitosamente."
                ]);
            }

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el cliente: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            DB::beginTransaction();

            // Primero eliminamos las medidas corporales relacionadas
            $cliente->medidasCorporales()->delete();
            
            // Si hay un usuario asociado, también lo eliminamos
            if ($cliente->user_id) {
                User::find($cliente->user_id)->delete();
            }
            
            // Luego eliminamos el cliente
            $cliente->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cliente eliminado exitosamente.'
                ]);
            }

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el cliente: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
