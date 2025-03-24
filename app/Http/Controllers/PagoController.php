<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Membresia;
use App\Models\MetodoPago;
use App\Models\User;
use App\Models\DuenoGimnasio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los parámetros de filtro
        $idUsuario = $request->input('id_usuario');
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));
        $mostrarTodos = $request->input('mostrar_todos', false);
        $metodoPago = $request->input('metodo_pago');
        
        // Obtener el usuario autenticado
        $usuarioActual = auth()->user();
        
        // Crear query base con relaciones necesarias
        $query = Pago::with([
            'membresia.tipoMembresia', 
            'usuario', 
            'metodoPago'
        ]);

        // Si el usuario es un dueño de gimnasio, filtrar solo sus pagos
        if ($usuarioActual->hasRole('dueño')) {
            $duenoGimnasio = DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                $query->whereHas('membresia.tipoMembresia', function($q) use ($gimnasiosIds) {
                    $q->whereIn('gimnasio_id', $gimnasiosIds);
                });
            }
        }
        
        // Aplicar filtro por usuario si está presente
        if ($idUsuario) {
            $query->where('id_usuario', $idUsuario);
        }
        
        // Aplicar filtro por método de pago si está presente
        if ($metodoPago) {
            $query->whereHas('metodoPago', function($q) use ($metodoPago) {
                $q->where('nombre_metodo', $metodoPago);
            });
        }
        
        // Aplicar filtro por fecha de pago (mes y año) si no se seleccionó "mostrar todos"
        if (!$mostrarTodos) {
            $query->whereMonth('fecha_pago', $mes)
                  ->whereYear('fecha_pago', $anio);
        }
        
        // Ejecutar la consulta ordenando por fecha de pago descendente
        $pagos = $query->orderBy('id_pago', 'desc')->get();
        
        // Obtener el total de pagos
        $totalPagos = $pagos->count();
        
        // Estadísticas de pagos
        $estadisticasQuery = DB::table('pagos')
            ->join('metodos_pago', 'pagos.id_metodo_pago', '=', 'metodos_pago.id_metodo_pago')
            ->join('membresias', 'pagos.id_membresia', '=', 'membresias.id_membresia')
            ->join('tipos_membresia', 'membresias.id_tipo_membresia', '=', 'tipos_membresia.id_tipo_membresia');

        // Si es dueño de gimnasio, filtrar las estadísticas
        if ($usuarioActual->hasRole('dueño')) {
            $duenoGimnasio = DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                $estadisticasQuery->whereIn('tipos_membresia.gimnasio_id', $gimnasiosIds);
            }
        }

        $estadisticasQuery->select(
            DB::raw('SUM(CASE WHEN metodos_pago.nombre_metodo = "tarjeta_credito" THEN pagos.monto ELSE 0 END) as total_tarjeta'),
            DB::raw('SUM(CASE WHEN metodos_pago.nombre_metodo = "efectivo" THEN pagos.monto ELSE 0 END) as total_efectivo'),
            DB::raw('SUM(CASE WHEN metodos_pago.nombre_metodo = "transferencia_bancaria" THEN pagos.monto ELSE 0 END) as total_transferencia'),
            DB::raw('SUM(pagos.monto) as total_general'),
            DB::raw('COUNT(CASE WHEN pagos.estado = "aprobado" THEN 1 END) as pagos_aprobados'),
            DB::raw('COUNT(CASE WHEN pagos.estado = "pendiente" THEN 1 END) as pagos_pendientes')
        );

        // Aplicar filtros si no se seleccionó "mostrar todos"
        if (!$mostrarTodos) {
            $estadisticasQuery->whereMonth('fecha_pago', $mes)
                            ->whereYear('fecha_pago', $anio);
        }

        if ($idUsuario) {
            $estadisticasQuery->where('id_usuario', $idUsuario);
        }

        $estadisticas = $estadisticasQuery->first();

        $montoTotalFormateado = number_format($estadisticas->total_general ?? 0, 2);
        $pagosTarjeta = number_format($estadisticas->total_tarjeta ?? 0, 2);
        $pagosEfectivo = number_format($estadisticas->total_efectivo ?? 0, 2);
        $pagosTransferencia = number_format($estadisticas->total_transferencia ?? 0, 2);
        $totalPagosAprobados = $estadisticas->pagos_aprobados ?? 0;
        $totalPagosPendientes = $estadisticas->pagos_pendientes ?? 0;
        
        // Obtener usuarios según el rol del usuario actual
        if ($usuarioActual->hasRole('dueño')) {
            $duenoGimnasio = DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                $usuarios = User::whereHas('cliente', function($q) use ($gimnasiosIds) {
                    $q->whereIn('gimnasio_id', $gimnasiosIds);
                })->get();
            } else {
                $usuarios = collect();
            }
        } else {
            $usuarios = User::all();
        }
        
        // Obtener solo las membresías con saldo pendiente según el rol
        if ($usuarioActual->hasRole('dueño')) {
            $duenoGimnasio = DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                $membresias = Membresia::with(['tipoMembresia', 'usuario'])
                    ->where('saldo_pendiente', '>', 0)
                    ->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                        $q->whereIn('gimnasio_id', $gimnasiosIds);
                    })
                    ->get();
            } else {
                $membresias = collect();
            }
        } else {
            $membresias = Membresia::with(['tipoMembresia', 'usuario'])
                ->where('saldo_pendiente', '>', 0)
                ->get();
        }
            
        $metodosPago = MetodoPago::where('activo', true)->get();
        
        // Datos para el selector de mes
        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
        
        // Datos para el selector de año (últimos 5 años)
        $anioActual = date('Y');
        $anios = [];
        for ($i = $anioActual - 4; $i <= $anioActual + 1; $i++) {
            $anios[] = $i;
        }
        
        return view('pagos.index', compact(
            'pagos', 
            'usuarios', 
            'membresias', 
            'metodosPago', 
            'idUsuario', 
            'mes', 
            'anio', 
            'meses', 
            'anios',
            'pagosTarjeta',
            'pagosEfectivo',
            'pagosTransferencia',
            'totalPagos',
            'totalPagosAprobados',
            'totalPagosPendientes',
            'montoTotalFormateado'
        ));
    }

    public function pendientes()
    {
        $usuarioActual = auth()->user();
        
        // Crear query base con relaciones necesarias
        $query = Pago::with([
            'membresia.tipoMembresia', 
            'usuario', 
            'metodoPago'
        ])->where('estado', 'pendiente');

        // Si el usuario es un dueño de gimnasio, filtrar solo sus pagos
        if ($usuarioActual->hasRole('dueño')) {
            $duenoGimnasio = DuenoGimnasio::where('user_id', $usuarioActual->id)->first();
            if ($duenoGimnasio) {
                $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                $query->whereHas('membresia.tipoMembresia', function($q) use ($gimnasiosIds) {
                    $q->whereIn('gimnasio_id', $gimnasiosIds);
                });
            }
        }

        $pagos = $query->orderBy('fecha_pago', 'desc')->get();
        
        return view('pagos.pendientes', compact('pagos'));
    }

    public function create()
    {
        $membresias = Membresia::all();
        $metodosPago = MetodoPago::where('activo', true)->get();
        return view('pagos.create', compact('membresias', 'metodosPago'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        try {
            // Verificar si el usuario es dueño y si la membresía pertenece a uno de sus gimnasios
            $user = auth()->user();
            if ($user->hasRole('dueño')) {
                $duenoGimnasio = DuenoGimnasio::where('user_id', $user->id)->first();
                if ($duenoGimnasio) {
                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                    
                    // Verificar si la membresía pertenece a uno de los gimnasios del dueño
                    $membresia = Membresia::with('tipoMembresia')
                        ->where('id_membresia', $request->id_membresia)
                        ->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                            $q->whereIn('gimnasio_id', $gimnasiosIds);
                        })
                        ->first();
                    
                    if (!$membresia) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para crear pagos para esta membresía.'
                        ], 403);
                    }
                }
            }

            $validated = $request->validate([
                'id_membresia' => 'required|exists:membresias,id_membresia',
                'id_usuario' => 'required|exists:users,id',
                'monto' => 'required|numeric|min:0',
                'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
                'estado_pago' => 'required|in:pendiente,aprobado,rechazado',
                'notas' => 'nullable|string|max:255',
                'fecha_pago' => 'nullable|date',
                'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
            ], [
                'id_membresia.required' => 'Debe seleccionar una membresía',
                'id_membresia.exists' => 'La membresía seleccionada no existe',
                'id_usuario.required' => 'Debe seleccionar un usuario',
                'id_usuario.exists' => 'El usuario seleccionado no existe',
                'monto.required' => 'El monto es requerido',
                'monto.numeric' => 'El monto debe ser un número',
                'monto.min' => 'El monto debe ser mayor a 0',
                'id_metodo_pago.required' => 'Debe seleccionar un método de pago',
                'id_metodo_pago.exists' => 'El método de pago seleccionado no existe',
                'estado_pago.required' => 'Debe seleccionar un estado',
                'estado_pago.in' => 'El estado seleccionado no es válido',
                'notas.max' => 'Las notas no pueden exceder los 255 caracteres',
                'fecha_pago.date' => 'La fecha debe ser válida',
                'comprobante.file' => 'El comprobante debe ser un archivo',
                'comprobante.mimes' => 'El comprobante debe ser un archivo JPG, JPEG, PNG o PDF',
                'comprobante.max' => 'El comprobante no debe exceder 5MB'
            ]);

            DB::beginTransaction();

            $pago = new Pago();
            $pago->id_membresia = $validated['id_membresia'];
            $pago->id_usuario = $validated['id_usuario'];
            $pago->monto = $validated['monto'];
            $pago->id_metodo_pago = $validated['id_metodo_pago'];
            $pago->estado = $validated['estado_pago'];
            $pago->notas = $validated['notas'] ?? null;
            $pago->fecha_pago = $validated['fecha_pago'] ?? now();
            
            // Si hay comprobante, guardarlo
            if ($request->hasFile('comprobante')) {
                $file = $request->file('comprobante');
                $filename = 'comprobante_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('comprobantes', $filename, 'public');
                $pago->comprobante_url = $path;
            }
            
            // Si el estado es aprobado, establecer la fecha de aprobación
            if ($pago->estado === 'aprobado') {
                $pago->fecha_aprobacion = now();
            }
            
            // Guardar el pago
            $pago->save();
            
            // Si el estado es aprobado, actualizar el saldo pendiente de la membresía
            if ($pago->estado === 'aprobado') {
                $membresia = Membresia::findOrFail($validated['id_membresia']);
                $nuevoSaldo = $membresia->saldo_pendiente - $pago->monto;
                $membresia->saldo_pendiente = max(0, $nuevoSaldo);
                $membresia->save();
            }

            DB::commit();

            // Cargar las relaciones necesarias para la respuesta
            $pago->load(['membresia.tipoMembresia', 'usuario', 'metodoPago']);

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente',
                'data' => [
                    'pago' => $pago,
                    'membresia' => $membresia ?? null
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Pago $pago)
    {
        $membresias = Membresia::all();
        $metodosPago = MetodoPago::where('activo', true)->get();
        $usuarios = User::all();
        return view('pagos.edit', compact('pago', 'membresias', 'metodosPago', 'usuarios'));
    }

    public function update(Request $request, Pago $pago)
    {
        try {
            // Verificar si el usuario es dueño y si la membresía pertenece a uno de sus gimnasios
            $user = auth()->user();
            if ($user->hasRole('dueño')) {
                $duenoGimnasio = DuenoGimnasio::where('user_id', $user->id)->first();
                if ($duenoGimnasio) {
                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                    
                    // Verificar si la membresía actual pertenece a uno de los gimnasios del dueño
                    $membresiaPago = $pago->membresia()->with('tipoMembresia')->first();
                    $perteneceADueno = $membresiaPago && $membresiaPago->tipoMembresia && 
                                     in_array($membresiaPago->tipoMembresia->gimnasio_id, $gimnasiosIds->toArray());
                    
                    if (!$perteneceADueno) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para modificar este pago.'
                        ], 403);
                    }
                    
                    // Si se está cambiando la membresía, verificar que la nueva también pertenezca al dueño
                    if ($request->filled('id_membresia') && $request->id_membresia != $pago->id_membresia) {
                        $nuevaMembresia = Membresia::with('tipoMembresia')
                            ->where('id_membresia', $request->id_membresia)
                            ->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                                $q->whereIn('gimnasio_id', $gimnasiosIds);
                            })
                            ->first();
                        
                        if (!$nuevaMembresia) {
                            return response()->json([
                                'success' => false,
                                'message' => 'No tienes permiso para asignar el pago a esta membresía.'
                            ], 403);
                        }
                    }
                }
            }

            $validated = $request->validate([
                'id_membresia' => 'required|exists:membresias,id_membresia',
                'id_usuario' => 'required|exists:users,id',
                'monto' => 'required|numeric|min:0',
                'fecha_pago' => 'required|date',
                'estado' => 'required|in:pendiente,aprobado,rechazado',
                'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
                'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'notas' => 'nullable|string',
                'fecha_aprobacion' => 'nullable|date'
            ]);

            DB::beginTransaction();

            if ($request->hasFile('comprobante')) {
                // Eliminar el archivo antiguo si existe
                if ($pago->comprobante_url && Storage::disk('public')->exists($pago->comprobante_url)) {
                    Storage::disk('public')->delete($pago->comprobante_url);
                }
                
                $file = $request->file('comprobante');
                $filename = 'comprobante_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('comprobantes', $filename, 'public');
                $validated['comprobante_url'] = $path;
            }

            if ($validated['estado'] === 'aprobado' && !$pago->fecha_aprobacion) {
                $validated['fecha_aprobacion'] = now();
            }

            // Si el estado cambia a aprobado, actualizar el saldo pendiente de la membresía
            if ($validated['estado'] === 'aprobado' && $pago->estado !== 'aprobado') {
                $membresia = Membresia::findOrFail($validated['id_membresia']);
                $nuevoSaldo = $membresia->saldo_pendiente - $validated['monto'];
                $membresia->saldo_pendiente = max(0, $nuevoSaldo);
                $membresia->save();
            }

            $pago->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago actualizado correctamente',
                'data' => [
                    'pago' => $pago,
                    'membresia' => $membresia ?? null
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a pending payment.
     */
    public function aprobar(Request $request, Pago $pago)
    {
        try {
            // Verificar si el usuario es dueño y si la membresía pertenece a uno de sus gimnasios
            $user = auth()->user();
            if ($user->hasRole('dueño')) {
                $duenoGimnasio = DuenoGimnasio::where('user_id', $user->id)->first();
                if ($duenoGimnasio) {
                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                    
                    // Verificar si la membresía pertenece a uno de los gimnasios del dueño
                    $membresiaPago = $pago->membresia()->with('tipoMembresia')->first();
                    $perteneceADueno = $membresiaPago && $membresiaPago->tipoMembresia && 
                                     in_array($membresiaPago->tipoMembresia->gimnasio_id, $gimnasiosIds->toArray());
                    
                    if (!$perteneceADueno) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para aprobar este pago.'
                        ], 403);
                    }
                }
            }

            if ($pago->estado !== 'pendiente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pago ya no está pendiente de aprobación.'
                ], 400);
            }

            DB::beginTransaction();

            $pago->estado = 'aprobado';
            $pago->fecha_aprobacion = now();
            $pago->save();

            // Actualizar el saldo pendiente de la membresía
            $membresia = $pago->membresia;
            if ($membresia) {
                $nuevoSaldo = $membresia->saldo_pendiente - $pago->monto;
                $membresia->saldo_pendiente = max(0, $nuevoSaldo);
                $membresia->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago aprobado correctamente',
                'data' => [
                    'pago' => $pago,
                    'membresia' => $membresia
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Pago $pago)
    {
        try {
            // Verificar si el usuario es dueño y si la membresía pertenece a uno de sus gimnasios
            $user = auth()->user();
            if ($user->hasRole('dueño')) {
                $duenoGimnasio = DuenoGimnasio::where('user_id', $user->id)->first();
                if ($duenoGimnasio) {
                    $gimnasiosIds = $duenoGimnasio->gimnasios->pluck('id_gimnasio');
                    
                    // Verificar si la membresía pertenece a uno de los gimnasios del dueño
                    $membresiaPago = $pago->membresia()->with('tipoMembresia')->first();
                    $perteneceADueno = $membresiaPago && $membresiaPago->tipoMembresia && 
                                     in_array($membresiaPago->tipoMembresia->gimnasio_id, $gimnasiosIds->toArray());
                    
                    if (!$perteneceADueno) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para eliminar este pago.'
                        ], 403);
                    }
                }
            }

            DB::beginTransaction();

            // Si el pago estaba aprobado, actualizar el saldo pendiente de la membresía
            if ($pago->estado === 'aprobado') {
                $membresia = $pago->membresia;
                if ($membresia) {
                    $membresia->saldo_pendiente += $pago->monto;
                    $membresia->save();
                }
            }

            // Eliminar el comprobante si existe
            if ($pago->comprobante_url && Storage::disk('public')->exists($pago->comprobante_url)) {
                Storage::disk('public')->delete($pago->comprobante_url);
            }

            $pago->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}