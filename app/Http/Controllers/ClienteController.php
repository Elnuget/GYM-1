<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Gimnasio;
use App\Models\Membresia;
use App\Models\User;
use App\Models\DuenoGimnasio;
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
        // Obtener el usuario autenticado
        $user = auth()->user();
        
        // Verificar si el usuario es un dueño de gimnasio
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Filtrar clientes que pertenecen a los gimnasios del dueño
            $clientes = Cliente::with('gimnasio')
                ->whereIn('gimnasio_id', $gimnasiosIds)
                ->get();
                
            // Obtener solo los gimnasios asociados al dueño
            $gimnasios = Gimnasio::whereIn('id_gimnasio', $gimnasiosIds)->get();
            
            // Filtrar membresías de los clientes de los gimnasios del dueño
            $todasLasMembresias = Membresia::with(['usuario', 'tipoMembresia'])
                ->whereHas('usuario', function($query) use ($gimnasiosIds) {
                    $query->whereHas('cliente', function($q) use ($gimnasiosIds) {
                        $q->whereIn('gimnasio_id', $gimnasiosIds);
                    });
                })
                ->get();
            
            // Filtrar pagos de las membresías de los clientes de los gimnasios del dueño
            $todosLosPagos = \App\Models\Pago::with(['usuario', 'membresia.tipoMembresia', 'metodoPago'])
                ->whereHas('usuario', function($query) use ($gimnasiosIds) {
                    $query->whereHas('cliente', function($q) use ($gimnasiosIds) {
                        $q->whereIn('gimnasio_id', $gimnasiosIds);
                    });
                })
                ->get();
            
            // Filtrar asistencias de los clientes de los gimnasios del dueño
            $todasLasAsistencias = \App\Models\Asistencia::with('cliente')
                ->whereHas('cliente', function($query) use ($gimnasiosIds) {
                    $query->whereIn('gimnasio_id', $gimnasiosIds);
                })
                ->get();
        } else {
            // Si no es dueño, mostrar todos los datos (para administradores)
            $clientes = Cliente::with('gimnasio')->get();
            $gimnasios = Gimnasio::all();
            $todasLasMembresias = Membresia::with(['usuario', 'tipoMembresia'])->get();
            $todosLosPagos = \App\Models\Pago::with(['usuario', 'membresia.tipoMembresia', 'metodoPago'])->get();
            $todasLasAsistencias = \App\Models\Asistencia::with('cliente')->get();
        }
        
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
            // Verificar si el usuario es dueño y si el gimnasio le pertenece
            $user = auth()->user();
            $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si el gimnasio pertenece al dueño
                $gimnasioPertenece = $dueno->gimnasios()
                    ->where('id_gimnasio', $request->gimnasio_id)
                    ->exists();
                    
                if (!$gimnasioPertenece) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para crear clientes en este gimnasio.'
                    ], 403);
                }
            }

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
                'message' => "Cliente {$cliente->nombre} creado exitosamente.\nSe ha creado una cuenta con el email: {$cliente->email}\nContraseña: gymflow2025",
                'cliente_id' => $user->id
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
            // Verificar si el usuario es dueño y si el gimnasio le pertenece
            $user = auth()->user();
            $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si el gimnasio pertenece al dueño
                $gimnasioPertenece = $dueno->gimnasios()
                    ->where('id_gimnasio', $request->gimnasio_id)
                    ->exists();
                    
                if (!$gimnasioPertenece) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para modificar clientes de este gimnasio.'
                        ], 403);
                    }
                    return redirect()->back()->with('error', 'No tienes permiso para modificar clientes de este gimnasio.');
                }
                
                // Verificar si el cliente actual pertenece a uno de los gimnasios del dueño
                $clientePertenece = $dueno->gimnasios()
                    ->where('id_gimnasio', $cliente->gimnasio_id)
                    ->exists();
                    
                if (!$clientePertenece) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para modificar este cliente.'
                        ], 403);
                    }
                    return redirect()->back()->with('error', 'No tienes permiso para modificar este cliente.');
                }
            }

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
            // Verificar si el usuario es dueño y si el cliente pertenece a uno de sus gimnasios
            $user = auth()->user();
            $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si el cliente pertenece a uno de los gimnasios del dueño
                $clientePertenece = $dueno->gimnasios()
                    ->where('id_gimnasio', $cliente->gimnasio_id)
                    ->exists();
                    
                if (!$clientePertenece) {
                    if (request()->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para eliminar este cliente.'
                        ], 403);
                    }
                    return redirect()->back()->with('error', 'No tienes permiso para eliminar este cliente.');
                }
            }

            DB::beginTransaction();

            // Si el cliente tiene un usuario asociado
            if ($cliente->user_id) {
                $user = User::find($cliente->user_id);
                
                if ($user) {
                    // Buscar todas las membresías del usuario
                    $membresias = Membresia::where('id_usuario', $user->id)->get();
                    
                    // Para cada membresía, eliminar primero sus pagos
                    foreach ($membresias as $membresia) {
                        // Eliminar los pagos asociados a la membresía
                        \App\Models\Pago::where('id_membresia', $membresia->id_membresia)->delete();
                        
                        // Eliminar la membresía
                        $membresia->delete();
                    }
                    
                    // Eliminar medidas corporales
                    $cliente->medidasCorporales()->delete();
                    
                    // Eliminar objetivos (si existen)
                    if (class_exists('\App\Models\ObjetivoCliente')) {
                        \App\Models\ObjetivoCliente::where('cliente_id', $cliente->id_cliente)->delete();
                    }
                    
                    // Eliminar asistencias del cliente (si existen)
                    if (class_exists('\App\Models\Asistencia')) {
                        \App\Models\Asistencia::where('cliente_id', $cliente->id_cliente)->delete();
                    }
                    
                    // Eliminar el cliente
                    $cliente->delete();
                    
                    // Eliminar el usuario
                    $user->delete();
                }
            } else {
                // Si no hay usuario asociado, simplemente eliminamos el cliente
                $cliente->delete();
            }

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
