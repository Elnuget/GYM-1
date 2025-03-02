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
        try {
            // Validar los datos del paso 3 (membresía)
            $validator = Validator::make($request->all(), [
                'membresia_nombre' => 'required|string|max:255',
                'membresia_precio' => 'required|numeric|min:0',
                'membresia_duracion' => 'required|integer|min:1',
                'membresia_tipo' => 'required|string|in:basica,estandar,premium',
                'membresia_descripcion' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Obtener datos de los pasos anteriores desde la sesión
            $paso1 = session('dueno_paso1', []);
            $paso2 = session('dueno_paso2', []);

            // Iniciar transacción
            DB::beginTransaction();

            try {
                // Obtener el usuario actual (dueño)
                $user = auth()->user();
                $duenoGimnasio = DuenoGimnasio::where('user_id', $user->id)->first();

                if (!$duenoGimnasio) {
                    throw new \Exception('No se encontró el registro del dueño del gimnasio');
                }

                // Crear el gimnasio si no existe
                $gimnasio = Gimnasio::where('dueno_id', $duenoGimnasio->id)->first();
                
                if (!$gimnasio) {
                    $gimnasio = new Gimnasio();
                    $gimnasio->nombre = $paso2['nombre_comercial'] ?? $request->nombre_comercial;
                    $gimnasio->telefono = $paso2['telefono_gimnasio'] ?? $request->telefono_gimnasio;
                    $gimnasio->direccion = $paso2['direccion_gimnasio'] ?? $request->direccion_gimnasio;
                    // La descripción se guarda en la tabla gimnasios, no en duenos_gimnasios
                    $gimnasio->descripcion = $paso2['descripcion'] ?? $request->descripcion;
                    $gimnasio->dueno_id = $duenoGimnasio->id;
                    
                    if (isset($paso2['logo_gimnasio'])) {
                        $gimnasio->logo = 'storage/' . $paso2['logo_gimnasio'];
                    } elseif ($request->hasFile('logo_gimnasio')) {
                        $path = $request->file('logo_gimnasio')->store('gimlogo', 'public');
                        $gimnasio->logo = 'storage/' . $path;
                    }
                    
                    $gimnasio->save();
                }

                // Crear la membresía inicial
                $membresia = new \App\Models\Membresia();
                $membresia->gimnasio_id = $gimnasio->id_gimnasio;
                $membresia->nombre = $request->membresia_nombre;
                $membresia->precio = $request->membresia_precio;
                $membresia->duracion_dias = $request->membresia_duracion;
                $membresia->tipo = $request->membresia_tipo;
                $membresia->descripcion = $request->membresia_descripcion;
                $membresia->activa = true;
                $membresia->save();

                // Marcar al usuario como configurado
                $user->configuracion_completa = true;
                $user->save();

                // Limpiar datos de sesión
                session()->forget(['dueno_paso1', 'dueno_paso2', 'current_step']);

                // Confirmar transacción
                DB::commit();

                // Redirigir al dashboard con mensaje de éxito
                return redirect()->route('dashboard')->with('success', '¡Registro completado con éxito! Ya puedes comenzar a administrar tu gimnasio.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Error al completar el registro: ' . $e->getMessage()])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error en el servidor: ' . $e->getMessage()])->withInput();
        }
    }

    protected function redirectTo()
    {
        if (auth()->user()->hasRole('cliente')) {
            return route('cliente.dashboard');
        } elseif (auth()->user()->hasRole('dueño')) {
            return route('dashboard');
        } elseif (auth()->user()->hasRole('empleado')) {
            return route('dashboard');
        } else {
            return route('dashboard');
        }
    }
    
    /**
     * Guarda los datos de un paso específico del formulario de registro de dueño
     */
    public function guardarPasoDueno(Request $request)
    {
        try {
            // Obtener el paso actual
            $currentStep = $request->input('current_step', 1);
            
            // Validar según el paso actual
            switch ($currentStep) {
                case 1:
                    $validator = Validator::make($request->all(), [
                        'telefono_personal' => 'required|string|max:20',
                        'direccion_personal' => 'required|string|max:255',
                        'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);
                    
                    if ($validator->fails()) {
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ], 422);
                    }
                    
                    // Guardar datos en sesión
                    session(['dueno_paso1' => [
                        'telefono_personal' => $request->telefono_personal,
                        'direccion_personal' => $request->direccion_personal,
                    ]]);
                    
                    // Guardar el paso actual en la sesión
                    session(['current_step' => 2]);
                    
                    // Procesar foto de perfil si existe
                    if ($request->hasFile('foto_perfil')) {
                        $path = $request->file('foto_perfil')->store('userphoto', 'public');
                        session(['dueno_paso1.foto_perfil' => $path]);
                    }
                    
                    // Actualizar datos del usuario
                    $user = auth()->user();
                    $user->telefono = $request->telefono_personal;
                    $user->direccion = $request->direccion_personal;
                    
                    // Guardar foto de perfil si existe
                    if ($request->hasFile('foto_perfil')) {
                        $user->foto_perfil = 'storage/' . $path;
                    }
                    
                    $user->save();
                    
                    // Actualizar o crear registro de dueño de gimnasio
                    $duenoGimnasio = DuenoGimnasio::where('user_id', $user->id)->first();
                    if (!$duenoGimnasio) {
                        $duenoGimnasio = new DuenoGimnasio();
                        $duenoGimnasio->user_id = $user->id;
                        $duenoGimnasio->save();
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Información personal guardada correctamente'
                    ]);
                    
                case 2:
                    $validator = Validator::make($request->all(), [
                        'nombre_comercial' => 'required|string|max:255',
                        'telefono_gimnasio' => 'required|string|max:20',
                        'direccion_gimnasio' => 'required|string|max:255',
                        'descripcion' => 'nullable|string|max:1000',
                        'logo_gimnasio' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);
                    
                    if ($validator->fails()) {
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->errors()
                        ], 422);
                    }
                    
                    // Guardar datos en sesión
                    session(['dueno_paso2' => [
                        'nombre_comercial' => $request->nombre_comercial,
                        'telefono_gimnasio' => $request->telefono_gimnasio,
                        'direccion_gimnasio' => $request->direccion_gimnasio,
                        'descripcion' => $request->descripcion,
                    ]]);
                    
                    // Guardar el paso actual en la sesión
                    session(['current_step' => 3]);
                    
                    // Procesar logo del gimnasio si existe
                    if ($request->hasFile('logo_gimnasio')) {
                        $path = $request->file('logo_gimnasio')->store('gimlogo', 'public');
                        session(['dueno_paso2.logo_gimnasio' => $path]);
                    }
                    
                    // Actualizar datos del dueño del gimnasio
                    $duenoGimnasio = DuenoGimnasio::where('user_id', auth()->id())->first();
                    if ($duenoGimnasio) {
                        // Actualizar solo los campos que existen en la tabla
                        $duenoGimnasio->nombre_comercial = $request->nombre_comercial;
                        $duenoGimnasio->telefono_gimnasio = $request->telefono_gimnasio;
                        $duenoGimnasio->direccion_gimnasio = $request->direccion_gimnasio;
                        // No intentamos guardar la descripción en la tabla duenos_gimnasios
                        // ya que esta columna no existe
                        
                        // Guardar el logo solo si se ha subido un nuevo archivo
                        if ($request->hasFile('logo_gimnasio')) {
                            // Ya hemos almacenado el archivo arriba, solo necesitamos actualizar la ruta
                            $duenoGimnasio->logo = 'storage/' . $path;
                        }
                        
                        $duenoGimnasio->save();
                    }
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Información del gimnasio guardada correctamente'
                    ]);
                    
                case 3:
                    // No es necesario validar aquí ya que el paso 3 se envía directamente al completar el registro
                    return response()->json([
                        'success' => true,
                        'message' => 'Datos de membresía listos para ser guardados'
                    ]);
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Paso no válido'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar los datos: ' . $e->getMessage()
            ], 500);
        }
    }
}
