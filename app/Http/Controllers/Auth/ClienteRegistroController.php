<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use App\Models\MedidaCorporal;
use App\Models\ObjetivoCliente;
use App\Models\Membresia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ClienteRegistroController extends Controller
{
    /**
     * Guardar un paso específico del registro de cliente
     */
    public function guardarPaso(Request $request)
    {
        try {
            $user = Auth::user();
            $cliente = Cliente::where('user_id', $user->id)->first();
            
            if (!$cliente) {
                $cliente = Cliente::create([
                    'user_id' => $user->id,
                ]);
            }
            
            $paso = $request->input('step', 1);
            $tieneMembresiaActiva = \App\Models\Membresia::where('id_usuario', $user->id)->exists();
            
            // Si el usuario tiene membresía activa, ajustamos el paso para que coincida con la nueva numeración
            if ($tieneMembresiaActiva) {
                switch ($paso) {
                    case 1:
                        // Información Personal
                        $request->validate([
                            'fecha_nacimiento' => 'required|date',
                            'telefono' => 'required|string|max:20',
                            'genero' => 'required|string|max:20',
                            'ocupacion' => 'required|string|max:100',
                            'direccion' => 'required|string|max:255',
                        ]);
                        
                        if ($request->hasFile('foto_perfil')) {
                            $request->validate([
                                'foto_perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                            ]);
                            
                            $path = $request->file('foto_perfil')->store('userphoto', 'public');
                            $user->foto_perfil = 'storage/' . $path;
                            $user->save();
                        }
                        
                        $cliente->update([
                            'fecha_nacimiento' => $request->fecha_nacimiento,
                            'telefono' => $request->telefono,
                            'genero' => $request->genero,
                            'ocupacion' => $request->ocupacion,
                            'direccion' => $request->direccion,
                        ]);
                        
                        $message = "Información personal guardada correctamente";
                        break;
                        
                    case 2:
                        // Medidas Corporales
                        $request->validate([
                            'peso' => 'required|numeric|min:0',
                            'altura' => 'required|numeric|min:0',
                            'cintura' => 'nullable|numeric|min:0',
                            'pecho' => 'nullable|numeric|min:0',
                            'biceps' => 'nullable|numeric|min:0',
                            'muslos' => 'nullable|numeric|min:0',
                            'pantorrillas' => 'nullable|numeric|min:0',
                        ]);
                        
                        MedidaCorporal::create([
                            'cliente_id' => $cliente->id_cliente,
                            'fecha_medicion' => now(),
                            'peso' => $request->peso,
                            'altura' => $request->altura,
                            'cintura' => $request->cintura ?? 0,
                            'pecho' => $request->pecho ?? 0,
                            'biceps' => $request->biceps ?? 0,
                            'muslos' => $request->muslos ?? 0,
                            'pantorrillas' => $request->pantorrillas ?? 0,
                        ]);
                        
                        $message = "Medidas corporales guardadas correctamente";
                        break;
                        
                    case 3:
                        // Objetivos Fitness
                        $request->validate([
                            'objetivo_principal' => 'required|string',
                            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
                            'dias_entrenamiento' => 'required|integer|min:1|max:7',
                        ]);
                        
                        ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
                            ->where('activo', true)
                            ->update(['activo' => false]);
                        
                        ObjetivoCliente::create([
                            'cliente_id' => $cliente->id_cliente,
                            'objetivo_principal' => $request->objetivo_principal,
                            'nivel_experiencia' => $request->nivel_experiencia,
                            'dias_entrenamiento' => $request->dias_entrenamiento,
                            'condiciones_medicas' => $request->condiciones_medicas,
                            'activo' => true
                        ]);
                        
                        $user->configuracion_completa = true;
                        $user->save();
                        
                        $message = "Objetivos fitness guardados correctamente";
                        break;
                }
            } else {
                // Usuario sin membresía activa
                switch ($paso) {
                    case 1:
                        // Membresía
                        $request->validate([
                            'id_tipo_membresia' => 'required|exists:tipos_membresia,id_tipo_membresia',
                            'precio_total' => 'required|numeric|min:0',
                            'fecha_compra' => 'required|date',
                            'fecha_vencimiento' => 'required|date|after:fecha_compra',
                            'visitas_permitidas' => 'nullable|integer|min:1',
                            'renovacion' => 'nullable|boolean',
                        ]);
                        
                        // Crear la membresía
                        \App\Models\Membresia::create([
                            'id_usuario' => $user->id,
                            'id_tipo_membresia' => $request->id_tipo_membresia,
                            'precio_total' => $request->precio_total,
                            'saldo_pendiente' => $request->precio_total,
                            'fecha_compra' => $request->fecha_compra,
                            'fecha_vencimiento' => $request->fecha_vencimiento,
                            'visitas_permitidas' => $request->visitas_permitidas,
                            'visitas_restantes' => $request->visitas_permitidas,
                            'renovacion' => $request->renovacion ?? false,
                            'estado' => 'activa'
                        ]);
                        
                        $message = "Membresía registrada correctamente";
                        break;
                        
                    case 2:
                        // Información Personal
                        $request->validate([
                            'fecha_nacimiento' => 'required|date',
                            'telefono' => 'required|string|max:20',
                            'genero' => 'required|string|max:20',
                            'ocupacion' => 'required|string|max:100',
                            'direccion' => 'required|string|max:255',
                        ]);
                        
                        if ($request->hasFile('foto_perfil')) {
                            $request->validate([
                                'foto_perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                            ]);
                            
                            $path = $request->file('foto_perfil')->store('userphoto', 'public');
                            $user->foto_perfil = 'storage/' . $path;
                            $user->save();
                        }
                        
                        $cliente->update([
                            'fecha_nacimiento' => $request->fecha_nacimiento,
                            'telefono' => $request->telefono,
                            'genero' => $request->genero,
                            'ocupacion' => $request->ocupacion,
                            'direccion' => $request->direccion,
                        ]);
                        
                        $message = "Información personal guardada correctamente";
                        break;
                        
                    case 3:
                        // Medidas Corporales
                        $request->validate([
                            'peso' => 'required|numeric|min:0',
                            'altura' => 'required|numeric|min:0',
                            'cintura' => 'nullable|numeric|min:0',
                            'pecho' => 'nullable|numeric|min:0',
                            'biceps' => 'nullable|numeric|min:0',
                            'muslos' => 'nullable|numeric|min:0',
                            'pantorrillas' => 'nullable|numeric|min:0',
                        ]);
                        
                        MedidaCorporal::create([
                            'cliente_id' => $cliente->id_cliente,
                            'fecha_medicion' => now(),
                            'peso' => $request->peso,
                            'altura' => $request->altura,
                            'cintura' => $request->cintura ?? 0,
                            'pecho' => $request->pecho ?? 0,
                            'biceps' => $request->biceps ?? 0,
                            'muslos' => $request->muslos ?? 0,
                            'pantorrillas' => $request->pantorrillas ?? 0,
                        ]);
                        
                        $message = "Medidas corporales guardadas correctamente";
                        break;
                        
                    case 4:
                        // Objetivos Fitness
                        $request->validate([
                            'objetivo_principal' => 'required|string',
                            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
                            'dias_entrenamiento' => 'required|integer|min:1|max:7',
                        ]);
                        
                        ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
                            ->where('activo', true)
                            ->update(['activo' => false]);
                        
                        ObjetivoCliente::create([
                            'cliente_id' => $cliente->id_cliente,
                            'objetivo_principal' => $request->objetivo_principal,
                            'nivel_experiencia' => $request->nivel_experiencia,
                            'dias_entrenamiento' => $request->dias_entrenamiento,
                            'condiciones_medicas' => $request->condiciones_medicas,
                            'activo' => true
                        ]);
                        
                        $user->configuracion_completa = true;
                        $user->save();
                        
                        $message = "Objetivos fitness guardados correctamente";
                        break;
                }
            }
            
            // Guardar el paso actual en la sesión
            session(['current_step' => $paso]);
            
            $maxPasos = $tieneMembresiaActiva ? 3 : 4;
            $siguientePaso = $paso < $maxPasos ? $paso + 1 : null;
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'next_step' => $siguientePaso,
                'redirect' => $paso === $maxPasos ? route('dashboard') : null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en el registro de cliente: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Error al guardar los datos. Por favor, inténtalo de nuevo."
            ], 422);
        }
    }
    
    /**
     * Completar el registro del cliente (método original)
     */
    public function completarRegistro(Request $request)
    {
        try {
            $user = Auth::user();
            $tieneMembresiaActiva = Membresia::where('id_usuario', $user->id)->exists();
            
            // Validación de campos según si tiene membresía o no
            $validationRules = [
                'fecha_nacimiento' => 'required|date',
                'telefono' => 'required|string|max:20',
                'genero' => 'required|string|max:20',
                'ocupacion' => 'required|string|max:100',
                'direccion' => 'required|string|max:255',
                'peso' => 'required|numeric|min:0',
                'altura' => 'required|numeric|min:0',
                'objetivo_principal' => 'required|string',
                'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
                'dias_entrenamiento' => 'required|integer|min:1|max:7',
                'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
            
            // Si no tiene membresía activa, agregar validaciones para la membresía
            if (!$tieneMembresiaActiva) {
                $validationRules = array_merge($validationRules, [
                    'id_tipo_membresia' => 'required|exists:tipos_membresia,id_tipo_membresia',
                    'precio_total' => 'required|numeric|min:0',
                    'fecha_compra' => 'required|date',
                    'fecha_vencimiento' => 'required|date|after:fecha_compra',
                    'visitas_permitidas' => 'nullable|integer|min:1',
                    'renovacion' => 'nullable|boolean',
                ]);
            }
            
            $request->validate($validationRules);
            
            // Procesar la foto de perfil
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('userphoto', 'public');
                $user->foto_perfil = 'storage/' . $path;
                $user->save();
                
                Log::info('Completar Registro - Foto guardada en: ' . $path);
            }
            
            // Buscar o crear el cliente
            $cliente = Cliente::where('user_id', $user->id)->first();
            if (!$cliente) {
                $cliente = Cliente::create([
                    'user_id' => $user->id,
                ]);
            }
            
            // Actualizar datos del cliente
            $cliente->update([
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'genero' => $request->genero,
                'ocupacion' => $request->ocupacion,
                'direccion' => $request->direccion,
            ]);
            
            // Crear registro de medidas corporales
            MedidaCorporal::create([
                'cliente_id' => $cliente->id_cliente,
                'fecha_medicion' => now(),
                'peso' => $request->peso,
                'altura' => $request->altura,
                'cintura' => $request->cintura ?? 0,
                'pecho' => $request->pecho ?? 0,
                'biceps' => $request->biceps ?? 0,
                'muslos' => $request->muslos ?? 0,
                'pantorrillas' => $request->pantorrillas ?? 0,
            ]);
            
            // Desactivar objetivos anteriores si existen
            ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
                ->where('activo', true)
                ->update(['activo' => false]);
                
            // Crear objetivo del cliente
            ObjetivoCliente::create([
                'cliente_id' => $cliente->id_cliente,
                'objetivo_principal' => $request->objetivo_principal,
                'nivel_experiencia' => $request->nivel_experiencia,
                'dias_entrenamiento' => $request->dias_entrenamiento,
                'condiciones_medicas' => $request->condiciones_medicas,
                'activo' => true
            ]);
            
            // Si no tiene membresía activa, crear una nueva
            if (!$tieneMembresiaActiva) {
                Membresia::create([
                    'id_usuario' => $user->id,
                    'id_tipo_membresia' => $request->id_tipo_membresia,
                    'precio_total' => $request->precio_total,
                    'saldo_pendiente' => $request->precio_total,
                    'fecha_compra' => $request->fecha_compra,
                    'fecha_vencimiento' => $request->fecha_vencimiento,
                    'visitas_permitidas' => $request->visitas_permitidas,
                    'visitas_restantes' => $request->visitas_permitidas,
                    'renovacion' => $request->renovacion ?? false,
                    'estado' => 'activa'
                ]);
            }
            
            // Marcar el registro como completo
            $user->configuracion_completa = true;
            $user->save();
            
            // Cambiar la redirección al dashboard del cliente
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Registro completado con éxito!',
                    'redirect' => route('cliente.dashboard')
                ]);
            }
            
            // Redirección normal al dashboard del cliente
            return redirect()->route('cliente.dashboard')
                ->with('success', '¡Registro completado con éxito!');
                
        } catch (\Exception $e) {
            Log::error('Error en completar registro de cliente: ' . $e->getMessage());
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al completar el registro. Por favor, inténtalo de nuevo.'
                ], 422);
            }
            
            return back()->withInput()
                ->withErrors(['error' => 'Error al completar el registro. Por favor, inténtalo de nuevo.']);
        }
    }
} 