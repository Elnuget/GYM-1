<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use App\Models\MedidaCorporal;
use App\Models\ObjetivoCliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            
            switch ($paso) {
                case 1:
                    // Validar datos del paso 1
                    $request->validate([
                        'fecha_nacimiento' => 'required|date',
                        'telefono' => 'required|string|max:20',
                        'genero' => 'required|string|max:20',
                        'ocupacion' => 'required|string|max:100',
                        'direccion' => 'required|string|max:255',
                    ]);
                    
                    // Procesar foto de perfil si se ha subido
                    if ($request->hasFile('foto_perfil')) {
                        $request->validate([
                            'foto_perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                        ]);
                        
                        // Guardar la foto usando store() como en RegisterController
                        $path = $request->file('foto_perfil')->store('userphoto', 'public');
                        
                        // Guardar la ruta en la base de datos con el prefijo 'storage/'
                        $user->foto_perfil = 'storage/' . $path;
                        $user->save();
                        
                        \Log::info('Foto guardada en: ' . $path);
                    }
                    
                    // Actualizar datos del cliente
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
                    // Validar datos del paso 2
                    $request->validate([
                        'peso' => 'required|numeric|min:0',
                        'altura' => 'required|numeric|min:0',
                        'cintura' => 'nullable|numeric|min:0',
                        'pecho' => 'nullable|numeric|min:0',
                        'biceps' => 'nullable|numeric|min:0',
                        'muslos' => 'nullable|numeric|min:0',
                        'pantorrillas' => 'nullable|numeric|min:0',
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
                    
                    $message = "Medidas corporales guardadas correctamente";
                    break;
                    
                case 3:
                    // Validar datos del paso 3
                    $request->validate([
                        'objetivo_principal' => 'required|string',
                        'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
                        'dias_entrenamiento' => 'required|integer|min:1|max:7',
                    ]);
                    
                    // Desactivar objetivos anteriores
                    ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
                        ->where('activo', true)
                        ->update(['activo' => false]);
                    
                    // Crear nuevo objetivo
                    ObjetivoCliente::create([
                        'cliente_id' => $cliente->id_cliente,
                        'objetivo_principal' => $request->objetivo_principal,
                        'nivel_experiencia' => $request->nivel_experiencia,
                        'dias_entrenamiento' => $request->dias_entrenamiento,
                        'condiciones_medicas' => $request->condiciones_medicas,
                        'activo' => true
                    ]);
                    
                    // Marcar el registro como completo cuando se complete el último paso
                    $user = Auth::user();
                    $user->configuracion_completa = true;
                    $user->save();
                    
                    $message = "Objetivos fitness guardados correctamente";
                    break;
            }
            
            // Guardar el paso actual en la sesión
            session(['current_step' => $paso]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'next_step' => $paso < 3 ? $paso + 1 : null,
                'redirect' => $paso === 3 ? route('dashboard') : null  // Añadir redirección al dashboard principal
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en el registro de cliente: ' . $e->getMessage());
            
            // Respuesta simplificada para evitar errores de JSON
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
            
            // Validación de todos los campos
            $request->validate([
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
            ]);
            
            // Procesar la foto de perfil
            if ($request->hasFile('foto_perfil')) {
                $request->validate([
                    'foto_perfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);
                
                // Guardar la foto usando store() como en RegisterController
                $path = $request->file('foto_perfil')->store('userphoto', 'public');
                
                // Guardar la ruta en la base de datos con el prefijo 'storage/'
                $user->foto_perfil = 'storage/' . $path;
                $user->save();
                
                \Log::info('Completar Registro - Foto guardada en: ' . $path);
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
                ->with('success', '¡Registro completado con éxito! Bienvenido a GymFlow.');
                
        } catch (\Exception $e) {
            \Log::error('Error en completarRegistro: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Respuesta para AJAX
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al completar el registro: ' . $e->getMessage()
                ], 422);
            }
            
            // Respuesta para formulario normal
            return back()->withInput()->with('error', 'Error al completar el registro: ' . $e->getMessage());
        }
    }
} 