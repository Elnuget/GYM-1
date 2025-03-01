<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\DuenoGimnasio;
use App\Models\Gimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function create()
    {
        $gimnasios = Gimnasio::all(['id_gimnasio', 'nombre']);
        return view('auth.register', compact('gimnasios'));
    }

    public function registerCliente(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'gimnasio_id' => ['required', 'exists:gimnasios,id_gimnasio'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            try {
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
                $cliente->save();

                DB::commit();

                event(new Registered($user));
                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => '¡Registro completado con éxito! Redirigiendo...',
                    'redirect' => route('onboarding.perfil')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el registro: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registerDueno(Request $request)
    {
        try {
            // Validar todos los campos
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'telefono' => 'required|string|max:20',
            ], [], [
                'name' => 'nombre',
                'email' => 'correo electrónico',
                'password' => 'contraseña',
                'telefono' => 'teléfono personal',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Iniciar transacción para asegurar que todo se guarde o nada
            DB::beginTransaction();

            try {
                // 1. Crear el usuario (dueño)
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'telefono' => $request->telefono,
                    'rol' => 'dueño'
                ]);

                // Asignar rol usando Spatie Permission
                $user->assignRole('dueño');

                // 2. Crear el registro de dueño_gimnasio
                $duenoGimnasio = DuenoGimnasio::create([
                    'user_id' => $user->id
                ]);

                // Si todo salió bien, confirmar la transacción
                DB::commit();

                // Registrar el evento
                event(new Registered($user));

                // Login automático
                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => '¡Registro completado con éxito! Redirigiendo...',
                    'redirect' => route('completar.registro')
                ]);

            } catch (\Exception $e) {
                // Si algo salió mal, deshacer todos los cambios
                DB::rollBack();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el registro: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registerGimnasio(Request $request)
    {
        try {
            // Validar los campos del gimnasio
            $validator = Validator::make($request->all(), [
                'dueno_id' => 'required|exists:duenos_gimnasio,id',
                'nombre_comercial' => 'required|string|max:255',
                'telefono_gimnasio' => 'required|string|max:20',
                'direccion_gimnasio' => 'required|string|max:255',
            ], [], [
                'dueno_id' => 'ID del dueño',
                'nombre_comercial' => 'nombre comercial del gimnasio',
                'telefono_gimnasio' => 'teléfono del gimnasio',
                'direccion_gimnasio' => 'dirección del gimnasio',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Iniciar transacción
            DB::beginTransaction();

            try {
                // Obtener el dueño del gimnasio
                $duenoGimnasio = DuenoGimnasio::find($request->dueno_id);

                if (!$duenoGimnasio) {
                    throw new \Exception('No se encontró el registro del dueño del gimnasio');
                }

                // Actualizar el dueño del gimnasio con la información del gimnasio
                $duenoGimnasio->update([
                    'nombre_comercial' => $request->nombre_comercial,
                    'telefono_gimnasio' => $request->telefono_gimnasio,
                    'direccion_gimnasio' => $request->direccion_gimnasio
                ]);

                // Crear el gimnasio
                $gimnasio = Gimnasio::create([
                    'nombre' => $request->nombre_comercial,
                    'telefono' => $request->telefono_gimnasio,
                    'direccion' => $request->direccion_gimnasio,
                    'dueno_id' => $duenoGimnasio->id
                ]);

                // Confirmar la transacción
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Gimnasio registrado con éxito'
                ]);

            } catch (\Exception $e) {
                // Si algo sale mal, deshacer los cambios
                DB::rollBack();
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el gimnasio: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registerEmpleado(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'telefono' => ['required', 'string', 'max:20'],
                'gimnasio_id' => ['required', 'exists:gimnasios,id_gimnasio'],
                'puesto' => ['required', 'string', 'max:100'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'especialidad' => ['nullable', 'string', 'max:255'],
                'certificaciones' => ['nullable', 'string', 'max:255'],
                'experiencia' => ['nullable', 'string'],
                'horario_disponibilidad' => ['nullable', 'string', 'max:255'],
            ], [], [
                'nombre' => 'nombre completo',
                'email' => 'correo electrónico',
                'telefono' => 'teléfono',
                'gimnasio_id' => 'gimnasio',
                'puesto' => 'puesto',
                'password' => 'contraseña',
                'especialidad' => 'especialidad',
                'certificaciones' => 'certificaciones',
                'experiencia' => 'experiencia',
                'horario_disponibilidad' => 'horario de disponibilidad',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Crear el usuario
                $user = User::create([
                    'name' => $request->nombre,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'rol' => 'entrenador',
                    'telefono' => $request->telefono,
                ]);

                // Asignar rol usando Spatie Permission
                $user->assignRole('entrenador');

                // Crear el registro de entrenador
                DB::table('entrenadores')->insert([
                    'user_id' => $user->id,
                    'gimnasio_id' => $request->gimnasio_id,
                    'especialidad' => $request->especialidad,
                    'certificaciones' => $request->certificaciones,
                    'telefono' => $request->telefono,
                    'experiencia' => $request->experiencia,
                    'horario_disponibilidad' => $request->horario_disponibilidad,
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();

                event(new Registered($user));
                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => '¡Registro completado con éxito! Redirigiendo...',
                    'redirect' => route('completar.registro')
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error al registrar el entrenador: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completarRegistro()
    {
        $user = auth()->user();
        $rol = $user->rol;
        
        // Redirigir a diferentes vistas según el rol
        if ($rol === 'cliente') {
            return view('auth.completar-registro.cliente', compact('user'));
        } elseif ($rol === 'empleado') {
            return view('auth.completar-registro.empleado', compact('user'));
        } elseif ($rol === 'dueño') {
            return view('auth.completar-registro.dueno', compact('user'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function mostrarFormularioCliente()
    {
        $user = auth()->user();
        if (!$user->hasRole('cliente')) {
            return redirect()->route('dashboard');
        }
        return view('auth.completar-registro.cliente', compact('user'));
    }
    
    public function mostrarFormularioEmpleado()
    {
        $user = auth()->user();
        if (!$user->hasRole('empleado') && !$user->hasRole('entrenador')) {
            return redirect()->route('dashboard');
        }
        return view('auth.completar-registro.empleado', compact('user'));
    }
    
    public function mostrarFormularioDueno()
    {
        $user = auth()->user();
        if (!$user->hasRole('dueño')) {
            return redirect()->route('dashboard');
        }
        return view('auth.completar-registro.dueno', compact('user'));
    }

    public function completarRegistroCliente(Request $request)
    {
        $user = auth()->user();
        $cliente = Cliente::where('user_id', $user->id)->first();
        
        $request->validate([
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'genero' => 'required|in:masculino,femenino,otro',
            'direccion' => 'required|string|max:255',
        ]);
        
        $cliente->update([
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'genero' => $request->genero,
            'direccion' => $request->direccion,
        ]);
        
        return redirect()->route('onboarding.perfil')->with('success', 'Perfil actualizado correctamente');
    }
    
    public function completarRegistroEmpleado(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'experiencia' => 'required|string',
            'especialidad' => 'nullable|string|max:100',
        ]);
        
        // Actualizar información del empleado
        DB::table('empleados')->where('user_id', $user->id)->update([
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'direccion' => $request->direccion,
            'experiencia' => $request->experiencia,
            'especialidad' => $request->especialidad,
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Perfil actualizado correctamente');
    }
    
    public function completarRegistroDueno(Request $request)
    {
        $user = auth()->user();
        $dueno = DuenoGimnasio::where('user_id', $user->id)->first();
        
        $request->validate([
            'nombre_comercial' => 'required|string|max:255',
            'telefono_gimnasio' => 'required|string|max:20',
            'direccion_gimnasio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_gimnasio' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'telefono_personal' => 'required|string|max:20',
            'direccion_personal' => 'required|string|max:255',
            'membresia_nombre' => 'required|string|max:255',
            'membresia_precio' => 'required|numeric|min:0',
            'membresia_duracion' => 'required|integer|min:1',
            'membresia_tipo' => 'required|in:basica,estandar,premium',
            'membresia_descripcion' => 'nullable|string',
        ]);
        
        // Procesar foto de perfil
        if ($request->hasFile('foto_perfil')) {
            $fotoPerfilPath = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $user->foto_perfil = 'storage/' . $fotoPerfilPath;
            $user->save();
        }
        
        // Actualizar información personal del dueño
        $user->telefono = $request->telefono_personal;
        $user->direccion = $request->direccion_personal;
        $user->save();
        
        // Actualizar información del dueño
        $dueno->update([
            'nombre_comercial' => $request->nombre_comercial,
            'telefono_gimnasio' => $request->telefono_gimnasio,
            'direccion_gimnasio' => $request->direccion_gimnasio,
        ]);
        
        // Crear el gimnasio si no existe
        $gimnasio = Gimnasio::where('dueno_id', $dueno->id_dueno)->first();
        
        if (!$gimnasio) {
            // Asegurarse de que dueno_id no sea nulo
            if (!$dueno->id_dueno) {
                // Log para depuración
                \Log::error('Error: id_dueno es nulo para el usuario ' . $user->id);
                return redirect()->back()->with('error', 'Error al registrar el gimnasio: ID de dueño no encontrado');
            }
            
            try {
                $gimnasio = Gimnasio::create([
                    'nombre' => $request->nombre_comercial,
                    'telefono' => $request->telefono_gimnasio,
                    'direccion' => $request->direccion_gimnasio,
                    'descripcion' => $request->descripcion,
                    'dueno_id' => $dueno->id_dueno // Usar id_dueno en lugar de id
                ]);
                
                // Procesar logo del gimnasio
                if ($request->hasFile('logo_gimnasio')) {
                    $logoPath = $request->file('logo_gimnasio')->store('logos_gimnasio', 'public');
                    $gimnasio->logo = 'storage/' . $logoPath;
                    $gimnasio->save();
                }
            } catch (\Exception $e) {
                \Log::error('Error al crear gimnasio: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error al registrar el gimnasio: ' . $e->getMessage());
            }
        } else {
            // Actualizar gimnasio existente
            $gimnasio->update([
                'nombre' => $request->nombre_comercial,
                'telefono' => $request->telefono_gimnasio,
                'direccion' => $request->direccion_gimnasio,
                'descripcion' => $request->descripcion,
            ]);
            
            // Procesar logo del gimnasio
            if ($request->hasFile('logo_gimnasio')) {
                $logoPath = $request->file('logo_gimnasio')->store('logos_gimnasio', 'public');
                $gimnasio->logo = 'storage/' . $logoPath;
                $gimnasio->save();
            }
        }
        
        // Crear tipo de membresía
        try {
            \App\Models\TipoMembresia::create([
                'gimnasio_id' => $gimnasio->id_gimnasio,
                'nombre' => $request->membresia_nombre,
                'descripcion' => $request->membresia_descripcion,
                'precio' => $request->membresia_precio,
                'duracion_dias' => $request->membresia_duracion,
                'tipo' => $request->membresia_tipo,
                'estado' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al crear tipo de membresía: ' . $e->getMessage());
            // No retornamos error aquí para no interrumpir el flujo, pero registramos el error
        }
        
        return redirect()->route('dashboard')->with('success', 'Información del gimnasio actualizada correctamente');
    }

    protected function redirectTo()
    {
        if (auth()->user()->hasRole('cliente')) {
            return route('cliente.bienvenida');
        }
        // ... otras redirecciones según el rol ...
        return route('dashboard');
    }
}
