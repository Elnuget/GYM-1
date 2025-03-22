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
            
            \Log::info('Guardando paso ' . $paso . ' para usuario ' . $user->id);
            \Log::info('Datos recibidos:', $request->all());
            
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
                        // Pago
                        $request->validate([
                            'monto_pago' => 'required|numeric|min:0',
                            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
                            'fecha_pago' => 'required|date',
                            'id_membresia' => 'required|exists:membresias,id_membresia',
                            'comprobante' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
                            'notas' => 'nullable|string|max:500'
                        ]);
                        
                        \Log::info('Validación de pago exitosa');
                        
                        // Procesar el comprobante si existe
                        $rutaComprobante = null;
                        if ($request->hasFile('comprobante')) {
                            $rutaComprobante = $request->file('comprobante')->store('comprobantes', 'public');
                            \Log::info('Comprobante guardado en: ' . $rutaComprobante);
                        }
                        
                        // Obtener la membresía
                        $membresia = \App\Models\Membresia::find($request->id_membresia);
                        if (!$membresia) {
                            throw new \Exception('No se encontró la membresía especificada');
                        }
                        
                        \Log::info('Membresía encontrada:', ['id' => $membresia->id_membresia]);
                        
                        // Crear el pago
                        $pago = \App\Models\Pago::create([
                            'id_membresia' => $membresia->id_membresia,
                            'id_usuario' => $user->id,
                            'monto' => $request->monto_pago,
                            'id_metodo_pago' => $request->id_metodo_pago,
                            'fecha_pago' => $request->fecha_pago,
                            'estado' => 'pendiente',
                            'comprobante' => $rutaComprobante ? 'storage/' . $rutaComprobante : null,
                            'notas' => $request->notas
                        ]);
                        
                        \Log::info('Pago creado:', ['id' => $pago->id_pago]);
                        
                        $message = "Pago registrado correctamente";
                        
                        // Determinar la URL de redirección con el siguiente paso
                        $redirectUrl = route('completar.registro.cliente.form') . '?paso=3';
                        
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => $redirectUrl
                        ]);
                        break;
                        
                    case 3:
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
                        
                        // Determinar la URL de redirección al paso 4
                        $redirectUrl = route('completar.registro.cliente.form') . '?paso=4';
                        
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => $redirectUrl
                        ]);
                        break;

                    case 4:
                        // Medidas Corporales
                        $request->validate([
                            'peso' => 'required|numeric|min:0',
                            'altura' => 'required|numeric|min:0',
                            'porcentaje_grasa' => 'nullable|numeric|min:0|max:100',
                            'porcentaje_musculo' => 'nullable|numeric|min:0|max:100',
                            'medida_cintura' => 'nullable|numeric|min:0',
                            'medida_cadera' => 'nullable|numeric|min:0',
                            'medida_pecho' => 'nullable|numeric|min:0',
                            'medida_brazos' => 'nullable|numeric|min:0',
                            'medida_piernas' => 'nullable|numeric|min:0'
                        ]);
                        
                        // Desactivar medidas anteriores
                        MedidaCorporal::where('cliente_id', $cliente->id_cliente)
                            ->where('activo', true)
                            ->update(['activo' => false]);
                        
                        // Crear nuevo registro de medidas
                        MedidaCorporal::create([
                            'cliente_id' => $cliente->id_cliente,
                            'peso' => $request->peso,
                            'altura' => $request->altura,
                            'porcentaje_grasa' => $request->porcentaje_grasa,
                            'porcentaje_musculo' => $request->porcentaje_musculo,
                            'medida_cintura' => $request->medida_cintura,
                            'medida_cadera' => $request->medida_cadera,
                            'medida_pecho' => $request->medida_pecho,
                            'medida_brazos' => $request->medida_brazos,
                            'medida_piernas' => $request->medida_piernas,
                            'activo' => true,
                            'fecha_medicion' => now()
                        ]);
                        
                        $message = "Medidas corporales guardadas correctamente";
                        break;
                        
                    case 5:
                        // Objetivos Fitness
                        $request->validate([
                            'objetivo_principal' => 'required|string',
                            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
                            'dias_entrenamiento' => 'required|integer|min:1|max:7',
                            'condiciones_medicas' => 'nullable|string'
                        ]);
                        
                        \Log::info('Guardando objetivos fitness para el usuario: ' . $user->id);
                        
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
                        
                        \Log::info('Objetivos fitness guardados correctamente');
                        
                        // Marcar el perfil como completado
                        $user->configuracion_completa = true;
                        $user->save();
                        
                        \Log::info('Perfil marcado como completado');
                        
                        $message = "Objetivos fitness guardados correctamente";
                        
                        // Redireccionar al dashboard
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => route('cliente.dashboard')
                        ]);
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
                        
                        // Determinar la URL de redirección al paso 2
                        $redirectUrl = route('completar.registro.cliente.form') . '?paso=2';
                        
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => $redirectUrl
                        ]);
                        break;
                        
                    case 2:
                        // Pago de la membresía
                        \Log::info('Procesando pago de membresía');
                        
                        $request->validate([
                            'monto_pago' => 'required|numeric|min:0',
                            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
                            'fecha_pago' => 'required|date',
                            'id_membresia' => 'required|exists:membresias,id_membresia',
                            'comprobante' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
                            'notas' => 'nullable|string'
                        ]);
                        
                        // Obtener la membresía específica
                        $membresia = \App\Models\Membresia::where('id_membresia', $request->id_membresia)
                            ->where('id_usuario', $user->id)
                            ->first();
                            
                        if (!$membresia) {
                            return response()->json([
                                'success' => false,
                                'message' => 'No se encontró la membresía especificada.'
                            ], 404);
                        }
                        
                        \Log::info('Membresía encontrada:', ['id' => $membresia->id_membresia]);
                        
                        // Procesar el comprobante si se proporciona
                        $rutaComprobante = null;
                        if ($request->hasFile('comprobante')) {
                            $rutaComprobante = $request->file('comprobante')->store('comprobantes', 'public');
                        }
                        
                        // Crear el pago
                        $pago = \App\Models\Pago::create([
                            'id_membresia' => $membresia->id_membresia,
                            'id_usuario' => $user->id,
                            'monto' => $request->monto_pago,
                            'id_metodo_pago' => $request->id_metodo_pago,
                            'fecha_pago' => $request->fecha_pago,
                            'estado' => 'pendiente',
                            'comprobante' => $rutaComprobante ? 'storage/' . $rutaComprobante : null,
                            'notas' => $request->notas
                        ]);
                        
                        \Log::info('Pago creado:', ['id' => $pago->id_pago]);
                        
                        $message = "Pago registrado correctamente";
                        
                        // Determinar la URL de redirección con el siguiente paso
                        $redirectUrl = route('completar.registro.cliente.form') . '?paso=3';
                        
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => $redirectUrl
                        ]);
                        break;
                        
                    case 3:
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
                        
                        // Determinar la URL de redirección al paso 4
                        $redirectUrl = route('completar.registro.cliente.form') . '?paso=4';
                        
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => $redirectUrl
                        ]);
                        break;
                        
                    case 4:
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
                        
                    case 5:
                        // Objetivos Fitness
                        $request->validate([
                            'objetivo_principal' => 'required|string',
                            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
                            'dias_entrenamiento' => 'required|integer|min:1|max:7',
                            'condiciones_medicas' => 'nullable|string'
                        ]);
                        
                        \Log::info('Guardando objetivos fitness para el usuario: ' . $user->id);
                        
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
                        
                        \Log::info('Objetivos fitness guardados correctamente');
                        
                        // Marcar el perfil como completado
                        $user->configuracion_completa = true;
                        $user->save();
                        
                        \Log::info('Perfil marcado como completado');
                        
                        $message = "Objetivos fitness guardados correctamente";
                        
                        // Redireccionar al dashboard
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'redirect' => route('cliente.dashboard')
                        ]);
                        break;
                }
            }
            
            // Guardar el paso actual en la sesión
            session(['current_step' => $paso]);
            
            $maxPasos = $tieneMembresiaActiva ? 5 : 5;
            $siguientePaso = $paso < $maxPasos ? $paso + 1 : null;
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'next_step' => $siguientePaso,
                'redirect' => $paso === $maxPasos ? route('dashboard') : null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al guardar paso: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar los datos: ' . $e->getMessage()
            ], 500);
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