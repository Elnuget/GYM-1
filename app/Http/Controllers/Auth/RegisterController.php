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
                    'redirect' => route('dashboard')
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
        return view('auth.completar-registro');
    }

    public function completarRegistroStore(Request $request)
    {
        // Aquí puedes agregar la lógica para guardar la información adicional
        return redirect()->route('dashboard')->with('success', 'Registro completado con éxito');
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
