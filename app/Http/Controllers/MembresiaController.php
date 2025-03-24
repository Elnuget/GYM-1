<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\User;
use App\Models\TipoMembresia;
use App\Models\MetodoPago;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembresiaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        
        // Inicializar la consulta base
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->orderBy('id_membresia', 'desc');
            
        // Verificar si el usuario es un dueño de gimnasio
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Filtrar membresías que pertenecen a los gimnasios del dueño
            $query->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            });
            
            // Filtrar usuarios para mostrar solo los de los gimnasios del dueño
            $usuarios = \App\Models\User::whereHas('cliente', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })->with(['cliente.gimnasio'])->get();
            
            // Filtrar tipos de membresía para mostrar solo los de los gimnasios del dueño
            $tiposMembresia = \App\Models\TipoMembresia::whereIn('gimnasio_id', $gimnasiosIds)
                ->where('estado', 1)
                ->get();
        } else {
            // Si no es dueño, mostrar todos los datos (para administradores)
            $usuarios = \App\Models\User::with(['cliente.gimnasio'])->get();
            $tiposMembresia = \App\Models\TipoMembresia::where('estado', 1)->get();
        }
            
        // Definir mes y año actuales como valores predeterminados
        $mesActual = date('n');
        $anioActual = date('Y');
        
        // Obtener valores de la solicitud o usar valores predeterminados
        $mes = $request->filled('mes') ? $request->input('mes') : $mesActual;
        $anio = $request->filled('anio') ? $request->input('anio') : $anioActual;
        $tipoFiltro = $request->input('tipo_filtro', 'creacion'); // valor predeterminado: creacion
        $idUsuario = $request->input('id_usuario'); // Filtro por usuario
        
        // Verificar si se solicitó "mostrar todos"
        $mostrarTodos = $request->has('mostrar_todos');
        
        // Filtro por usuario si está presente
        if ($idUsuario) {
            $query->where('id_usuario', $idUsuario);
        }
        // Aplicar filtros de fecha a menos que se haya solicitado mostrar todos
        elseif (!$mostrarTodos) {
            // Usar el campo correcto según el tipo de filtro
            $campoFecha = $tipoFiltro === 'vencimiento' ? 'fecha_vencimiento' : 'fecha_compra';
            
            $query->whereYear($campoFecha, $anio)
                  ->whereMonth($campoFecha, $mes);
        }
        
        // Obtener todas las membresías sin paginación
        $membresias = $query->get();
        $metodosPago = \App\Models\MetodoPago::all();
        
        // Datos para el selector de mes/año
        $meses = [
            1 => 'Enero', 
            2 => 'Febrero', 
            3 => 'Marzo', 
            4 => 'Abril', 
            5 => 'Mayo', 
            6 => 'Junio', 
            7 => 'Julio', 
            8 => 'Agosto', 
            9 => 'Septiembre', 
            10 => 'Octubre', 
            11 => 'Noviembre', 
            12 => 'Diciembre'
        ];
        
        // Generar años desde el actual hasta 3 años en el futuro
        $anios = range($anioActual - 2, $anioActual + 3);
        
        // Obtener usuario seleccionado si existe
        $usuarioSeleccionado = $idUsuario ? \App\Models\User::find($idUsuario) : null;
        
        // Calcular estadísticas
        $fechaActual = now();
        
        if ($dueno) {
            // Estadísticas filtradas por gimnasios del dueño
            $membresiasVencidasMes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
            $membresiasActivas = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '>=', $fechaActual)
            ->count();
            
            $totalSaldosPendientes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->sum('saldo_pendiente');
            
            $membresiasPendientesPago = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->count();
            
            // Membresías sin renovar
            $usuariosConMembresias = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->distinct('id_usuario')
            ->pluck('id_usuario');
        } else {
            // Estadísticas para todos los gimnasios
            $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
                ->whereMonth('fecha_vencimiento', $fechaActual->month)
                ->whereYear('fecha_vencimiento', $fechaActual->year)
                ->count();
                
            $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
            
            $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
            $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
            
            $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        }
        
        $membresiasNoRenovadas = 0;
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            // Obtener la membresía más reciente de este usuario
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            if ($ultimaMembresia && 
                $ultimaMembresia->fecha_vencimiento && 
                $ultimaMembresia->fecha_vencimiento < $fechaActual &&
                ($ultimaMembresia->fecha_vencimiento->month != $fechaActual->month || 
                 $ultimaMembresia->fecha_vencimiento->year != $fechaActual->year)) {
                $membresiasNoRenovadas++;
            }
        }
        
        return view('membresias.index', compact(
            'membresias', 
            'usuarios', 
            'tiposMembresia', 
            'metodosPago', 
            'meses', 
            'anios',
            'mes',
            'anio',
            'mostrarTodos',
            'tipoFiltro',
            'idUsuario',
            'usuarioSeleccionado',
            'membresiasVencidasMes',
            'membresiasNoRenovadas',
            'membresiasActivas',
            'totalSaldosPendientes',
            'membresiasPendientesPago'
        ));
    }

    public function create()
    {
        $usuarios = User::all();
        $tiposMembresia = TipoMembresia::all();
        return view('membresias.create', compact('usuarios', 'tiposMembresia'));
    }

    public function store(Request $request)
    {
        try {
            // Verificar si el usuario es un dueño de gimnasio
            $user = auth()->user();
            $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Obtener el gimnasio asociado al tipo de membresía
                $tipoMembresia = \App\Models\TipoMembresia::find($request->id_tipo_membresia);
                if (!$tipoMembresia) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El tipo de membresía no existe.'
                    ], 404);
                }
                
                // Verificar si el gimnasio pertenece al dueño
                $gimnasioPertenece = $dueno->gimnasios()
                    ->where('id_gimnasio', $tipoMembresia->gimnasio_id)
                    ->exists();
                    
                if (!$gimnasioPertenece) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para crear membresías en este gimnasio.'
                    ], 403);
                }
            }

            $validated = $request->validate([
                'id_usuario' => 'required|exists:users,id',
                'id_tipo_membresia' => 'required|exists:tipos_membresia,id_tipo_membresia',
                'precio_total' => 'required|numeric|min:0',
                'saldo_pendiente' => 'required|numeric|min:0',
                'fecha_compra' => 'required|date',
                'fecha_vencimiento' => 'required|date|after:fecha_compra',
                'visitas_permitidas' => 'nullable|integer',
                'renovacion' => 'boolean',
                // Campos del pago
                'monto_pago' => 'required|numeric|min:0',
                'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
                'estado_pago' => 'required|in:pendiente,aprobado,rechazado',
                'notas' => 'nullable|string|max:255',
                'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
            ]);

            DB::beginTransaction();

            // Crear la membresía
            if ($validated['visitas_permitidas']) {
                $validated['visitas_restantes'] = $validated['visitas_permitidas'];
            }

            $membresia = Membresia::create([
                'id_usuario' => $validated['id_usuario'],
                'id_tipo_membresia' => $validated['id_tipo_membresia'],
                'precio_total' => $validated['precio_total'],
                'saldo_pendiente' => $validated['saldo_pendiente'],
                'fecha_compra' => $validated['fecha_compra'],
                'fecha_vencimiento' => $validated['fecha_vencimiento'],
                'visitas_permitidas' => $validated['visitas_permitidas'] ?? null,
                'visitas_restantes' => $validated['visitas_restantes'] ?? null,
                'renovacion' => $validated['renovacion'] ?? false
            ]);

            // Crear el pago
            $pago = new \App\Models\Pago();
            $pago->id_membresia = $membresia->id_membresia;
            $pago->id_usuario = $validated['id_usuario'];
            $pago->monto = $validated['monto_pago'];
            $pago->fecha_pago = now();
            $pago->estado = $validated['estado_pago'];
            $pago->id_metodo_pago = $validated['id_metodo_pago'];
            $pago->notas = $validated['notas'] ?? null;

            // Si hay comprobante, guardarlo
            if ($request->hasFile('comprobante')) {
                $file = $request->file('comprobante');
                $filename = 'comprobante_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('comprobantes', $filename, 'public');
                $pago->comprobante_url = $path;
            }

            // Si el pago es aprobado, establecer la fecha de aprobación
            if ($pago->estado === 'aprobado') {
                $pago->fecha_aprobacion = now();
                // Actualizar el saldo pendiente de la membresía
                $membresia->saldo_pendiente = max(0, $membresia->saldo_pendiente - $pago->monto);
                $membresia->save();
            }

            $pago->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Membresía y pago creados exitosamente',
                'data' => [
                    'membresia' => $membresia,
                    'pago' => $pago
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
                'message' => 'Error al crear la membresía y el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit(Membresia $membresia)
    {
        $membresia->fecha_compra = $membresia->fecha_compra->format('Y-m-d');
        $membresia->fecha_vencimiento = $membresia->fecha_vencimiento->format('Y-m-d');
        return response()->json($membresia);
    }

    public function update(Request $request, Membresia $membresia)
    {
        try {
            // Verificar si el usuario es un dueño de gimnasio
            $user = auth()->user();
            $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si la membresía actual pertenece a uno de los gimnasios del dueño
                $membresiaPertenece = $dueno->gimnasios()
                    ->whereHas('tiposMembresia', function($q) use ($membresia) {
                        $q->where('id_tipo_membresia', $membresia->id_tipo_membresia);
                    })
                    ->exists();
                    
                if (!$membresiaPertenece) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes permiso para modificar esta membresía.'
                    ], 403);
                }
                
                // Si se está cambiando el tipo de membresía, verificar que el nuevo tipo pertenezca a uno de los gimnasios del dueño
                if ($request->id_tipo_membresia != $membresia->id_tipo_membresia) {
                    $nuevoTipoPertenece = $dueno->gimnasios()
                        ->whereHas('tiposMembresia', function($q) use ($request) {
                            $q->where('id_tipo_membresia', $request->id_tipo_membresia);
                        })
                        ->exists();
                        
                    if (!$nuevoTipoPertenece) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No tienes permiso para asignar este tipo de membresía.'
                        ], 403);
                    }
                }
            }

            $validated = $request->validate([
                'id_usuario' => 'required|exists:users,id',
                'id_tipo_membresia' => 'required|exists:tipos_membresia,id_tipo_membresia',
                'precio_total' => 'required|numeric|min:0',
                'saldo_pendiente' => 'required|numeric|min:0',
                'fecha_compra' => 'required|date',
                'fecha_vencimiento' => 'required|date|after:fecha_compra',
                'visitas_permitidas' => 'nullable|integer',
                'renovacion' => 'boolean'
            ]);

            if ($validated['visitas_permitidas']) {
                $validated['visitas_restantes'] = $validated['visitas_permitidas'];
            }

            $membresia->update($validated);

            return redirect()->route('membresias.index')
                ->with('success', 'Membresía actualizada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la membresía: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Membresia $membresia)
    {
        try {
            // Verificar si el usuario es un dueño de gimnasio
            $user = auth()->user();
            $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
            
            if ($dueno) {
                // Verificar si la membresía pertenece a uno de los gimnasios del dueño
                $membresiaPertenece = $dueno->gimnasios()
                    ->whereHas('tiposMembresia', function($q) use ($membresia) {
                        $q->where('id_tipo_membresia', $membresia->id_tipo_membresia);
                    })
                    ->exists();
                    
                if (!$membresiaPertenece) {
                    return redirect()->back()
                        ->with('error', 'No tienes permiso para eliminar esta membresía.');
                }
            }

            $membresia->delete();

            return redirect()->route('membresias.index')
                ->with('success', 'Membresía eliminada exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la membresía: ' . $e->getMessage());
        }
    }

    public function registrarVisita(Membresia $membresia)
    {
        $membresia->registrarVisita();
        
        return redirect()->back()
            ->with('success', 'Visita registrada exitosamente');
    }

    /**
     * Get all payments for a specific membership.
     */
    public function pagos(Membresia $membresia)
    {
        return response()->json(
            $membresia->pagos()
                ->with(['metodoPago'])
                ->orderBy('fecha_pago', 'desc')
                ->get()
        );
    }
    
    /**
     * Mostrar las membresías vencidas en el mes actual.
     */
    public function vencidas()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        // Obtener fecha actual
        $fechaActual = now();
        
        // Inicializar la consulta base
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio']);
        
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Filtrar membresías que pertenecen a los gimnasios del dueño
            $query->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            });
            
            // Filtrar usuarios y tipos de membresía
            $usuarios = \App\Models\User::whereHas('cliente', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })->with(['cliente.gimnasio'])->get();
            
            $tiposMembresia = \App\Models\TipoMembresia::whereIn('gimnasio_id', $gimnasiosIds)
                ->where('estado', 1)
                ->get();
        } else {
            $usuarios = \App\Models\User::with(['cliente.gimnasio'])->get();
            $tiposMembresia = \App\Models\TipoMembresia::where('estado', 1)->get();
        }
        
        // Aplicar filtros de fecha
        $query->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->orderBy('fecha_vencimiento', 'desc');
            
        $membresias = $query->get();
        $metodosPago = \App\Models\MetodoPago::all();
        
        // Datos para el selector de mes/año
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarVencidas = true;
        $idUsuario = null;
        $mostrarTodos = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        if ($dueno) {
            $membresiasVencidasMes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
            $membresiasActivas = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '>=', $fechaActual)
            ->count();
            
            $totalSaldosPendientes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->sum('saldo_pendiente');
            
            $membresiasPendientesPago = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->count();
            
            $usuariosConMembresias = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->distinct('id_usuario')
            ->pluck('id_usuario');
        } else {
            $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
                ->whereMonth('fecha_vencimiento', $fechaActual->month)
                ->whereYear('fecha_vencimiento', $fechaActual->year)
                ->count();
                
            $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
            
            $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
            $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
            
            $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        }
        
        $membresiasNoRenovadas = 0;
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            if ($ultimaMembresia && 
                $ultimaMembresia->fecha_vencimiento && 
                $ultimaMembresia->fecha_vencimiento < $fechaActual &&
                ($ultimaMembresia->fecha_vencimiento->month != $fechaActual->month || 
                 $ultimaMembresia->fecha_vencimiento->year != $fechaActual->year)) {
                $membresiasNoRenovadas++;
            }
        }
        
        return view('membresias.index', compact(
            'membresias', 
            'usuarios', 
            'tiposMembresia', 
            'metodosPago', 
            'meses', 
            'anios',
            'mes',
            'anio',
            'mostrarVencidas',
            'membresiasVencidasMes',
            'membresiasNoRenovadas',
            'idUsuario',
            'mostrarTodos',
            'tipoFiltro',
            'usuarioSeleccionado',
            'membresiasActivas',
            'totalSaldosPendientes',
            'membresiasPendientesPago'
        ));
    }

    /**
     * Mostrar las membresías sin renovar.
     */
    public function sinRenovar()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        // Obtener fecha actual
        $fechaActual = now();
        
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Obtener usuarios con membresías de los gimnasios del dueño
            $usuariosConMembresias = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->distinct('id_usuario')
            ->pluck('id_usuario');
            
            // Filtrar usuarios y tipos de membresía
            $usuarios = \App\Models\User::whereHas('cliente', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })->with(['cliente.gimnasio'])->get();
            
            $tiposMembresia = \App\Models\TipoMembresia::whereIn('gimnasio_id', $gimnasiosIds)
                ->where('estado', 1)
                ->get();
        } else {
            $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
            $usuarios = \App\Models\User::with(['cliente.gimnasio'])->get();
            $tiposMembresia = \App\Models\TipoMembresia::where('estado', 1)->get();
        }
        
        $usuariosSinRenovar = [];
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            if ($ultimaMembresia && 
                $ultimaMembresia->fecha_vencimiento && 
                $ultimaMembresia->fecha_vencimiento < $fechaActual &&
                ($ultimaMembresia->fecha_vencimiento->month != $fechaActual->month || 
                 $ultimaMembresia->fecha_vencimiento->year != $fechaActual->year)) {
                $usuariosSinRenovar[] = $ultimaMembresia->id_membresia;
            }
        }
        
        // Consultar membresías sin renovar
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->whereIn('id_membresia', $usuariosSinRenovar);
            
        if ($dueno) {
            $query->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            });
        }
        
        $membresias = $query->orderBy('fecha_vencimiento', 'desc')->get();
        $metodosPago = \App\Models\MetodoPago::all();
        
        // Datos para el selector de mes/año
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarSinRenovar = true;
        $idUsuario = null;
        $mostrarTodos = false;
        $mostrarVencidas = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        if ($dueno) {
            $membresiasVencidasMes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
            $membresiasActivas = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '>=', $fechaActual)
            ->count();
            
            $totalSaldosPendientes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->sum('saldo_pendiente');
            
            $membresiasPendientesPago = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->count();
        } else {
            $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
                ->whereMonth('fecha_vencimiento', $fechaActual->month)
                ->whereYear('fecha_vencimiento', $fechaActual->year)
                ->count();
                
            $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
            
            $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
            $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
        }
        
        $membresiasNoRenovadas = count($usuariosSinRenovar);
        
        return view('membresias.index', compact(
            'membresias', 
            'usuarios', 
            'tiposMembresia', 
            'metodosPago', 
            'meses', 
            'anios',
            'mes',
            'anio',
            'mostrarSinRenovar',
            'mostrarVencidas',
            'membresiasVencidasMes',
            'membresiasNoRenovadas',
            'membresiasActivas',
            'idUsuario',
            'mostrarTodos',
            'tipoFiltro',
            'usuarioSeleccionado',
            'totalSaldosPendientes',
            'membresiasPendientesPago'
        ));
    }

    /**
     * Mostrar solo las membresías activas.
     */
    public function activas()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        // Obtener fecha actual
        $fechaActual = now();
        
        // Inicializar la consulta base
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->whereDate('fecha_vencimiento', '>=', $fechaActual);
            
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Filtrar membresías que pertenecen a los gimnasios del dueño
            $query->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            });
            
            // Filtrar usuarios y tipos de membresía
            $usuarios = \App\Models\User::whereHas('cliente', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })->with(['cliente.gimnasio'])->get();
            
            $tiposMembresia = \App\Models\TipoMembresia::whereIn('gimnasio_id', $gimnasiosIds)
                ->where('estado', 1)
                ->get();
        } else {
            $usuarios = \App\Models\User::with(['cliente.gimnasio'])->get();
            $tiposMembresia = \App\Models\TipoMembresia::where('estado', 1)->get();
        }
        
        $membresias = $query->orderBy('fecha_vencimiento', 'asc')->get();
        $metodosPago = \App\Models\MetodoPago::all();
        
        // Datos para el selector de mes/año
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarActivas = true;
        $idUsuario = null;
        $mostrarTodos = false;
        $mostrarVencidas = false;
        $mostrarSinRenovar = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        if ($dueno) {
            $membresiasVencidasMes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
            $membresiasActivas = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '>=', $fechaActual)
            ->count();
            
            $totalSaldosPendientes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->sum('saldo_pendiente');
            
            $membresiasPendientesPago = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->count();
            
            $usuariosConMembresias = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->distinct('id_usuario')
            ->pluck('id_usuario');
        } else {
            $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
                ->whereMonth('fecha_vencimiento', $fechaActual->month)
                ->whereYear('fecha_vencimiento', $fechaActual->year)
                ->count();
                
            $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
            
            $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
            $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
            
            $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        }
        
        $membresiasNoRenovadas = 0;
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            if ($ultimaMembresia && 
                $ultimaMembresia->fecha_vencimiento && 
                $ultimaMembresia->fecha_vencimiento < $fechaActual &&
                ($ultimaMembresia->fecha_vencimiento->month != $fechaActual->month || 
                 $ultimaMembresia->fecha_vencimiento->year != $fechaActual->year)) {
                $membresiasNoRenovadas++;
            }
        }
        
        return view('membresias.index', compact(
            'membresias', 
            'usuarios', 
            'tiposMembresia', 
            'metodosPago', 
            'meses', 
            'anios',
            'mes',
            'anio',
            'mostrarActivas',
            'mostrarVencidas',
            'mostrarSinRenovar',
            'membresiasVencidasMes',
            'membresiasNoRenovadas',
            'membresiasActivas',
            'idUsuario',
            'mostrarTodos',
            'tipoFiltro',
            'usuarioSeleccionado',
            'totalSaldosPendientes',
            'membresiasPendientesPago'
        ));
    }

    /**
     * Mostrar solo las membresías con saldo pendiente.
     */
    public function saldosPendientes()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        $dueno = \App\Models\DuenoGimnasio::where('user_id', $user->id)->first();
        
        // Obtener fecha actual
        $fechaActual = now();
        
        // Inicializar la consulta base
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->where('saldo_pendiente', '>', 0);
            
        if ($dueno) {
            // Obtener los IDs de los gimnasios asociados al dueño
            $gimnasiosIds = $dueno->gimnasios->pluck('id_gimnasio');
            
            // Filtrar membresías que pertenecen a los gimnasios del dueño
            $query->whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            });
            
            // Filtrar usuarios y tipos de membresía
            $usuarios = \App\Models\User::whereHas('cliente', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })->with(['cliente.gimnasio'])->get();
            
            $tiposMembresia = \App\Models\TipoMembresia::whereIn('gimnasio_id', $gimnasiosIds)
                ->where('estado', 1)
                ->get();
        } else {
            $usuarios = \App\Models\User::with(['cliente.gimnasio'])->get();
            $tiposMembresia = \App\Models\TipoMembresia::where('estado', 1)->get();
        }
        
        $membresias = $query->orderBy('saldo_pendiente', 'desc')->get();
        $metodosPago = \App\Models\MetodoPago::all();
        
        // Datos para el selector de mes/año
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarSaldosPendientes = true;
        $idUsuario = null;
        $mostrarTodos = false;
        $mostrarVencidas = false;
        $mostrarSinRenovar = false;
        $mostrarActivas = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        if ($dueno) {
            $membresiasVencidasMes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
            $membresiasActivas = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->whereDate('fecha_vencimiento', '>=', $fechaActual)
            ->count();
            
            $totalSaldosPendientes = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->sum('saldo_pendiente');
            
            $membresiasPendientesPago = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->where('saldo_pendiente', '>', 0)
            ->count();
            
            $usuariosConMembresias = Membresia::whereHas('tipoMembresia', function($q) use ($gimnasiosIds) {
                $q->whereIn('gimnasio_id', $gimnasiosIds);
            })
            ->distinct('id_usuario')
            ->pluck('id_usuario');
        } else {
            $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
                ->whereMonth('fecha_vencimiento', $fechaActual->month)
                ->whereYear('fecha_vencimiento', $fechaActual->year)
                ->count();
                
            $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
            
            $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
            $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
            
            $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        }
        
        $membresiasNoRenovadas = 0;
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            if ($ultimaMembresia && 
                $ultimaMembresia->fecha_vencimiento && 
                $ultimaMembresia->fecha_vencimiento < $fechaActual &&
                ($ultimaMembresia->fecha_vencimiento->month != $fechaActual->month || 
                 $ultimaMembresia->fecha_vencimiento->year != $fechaActual->year)) {
                $membresiasNoRenovadas++;
            }
        }
        
        return view('membresias.index', compact(
            'membresias', 
            'usuarios', 
            'tiposMembresia', 
            'metodosPago', 
            'meses', 
            'anios',
            'mes',
            'anio',
            'mostrarActivas',
            'mostrarVencidas',
            'mostrarSinRenovar',
            'mostrarSaldosPendientes',
            'membresiasVencidasMes',
            'membresiasNoRenovadas',
            'membresiasActivas',
            'totalSaldosPendientes',
            'membresiasPendientesPago',
            'idUsuario',
            'mostrarTodos',
            'tipoFiltro',
            'usuarioSeleccionado'
        ));
    }

    /**
     * Renovar la última membresía de un usuario.
     */
    public function renovar(Request $request, $id_usuario)
    {
        try {
            DB::beginTransaction();
            
            // Obtener la última membresía del usuario
            $ultimaMembresia = Membresia::where('id_usuario', $id_usuario)
                ->orderBy('fecha_vencimiento', 'desc')
                ->firstOrFail();
            
            // Crear nueva membresía con los mismos datos pero fechas actualizadas
            $nuevaMembresia = Membresia::create([
                'id_usuario' => $id_usuario,
                'id_tipo_membresia' => $ultimaMembresia->id_tipo_membresia,
                'precio_total' => $ultimaMembresia->precio_total,
                'saldo_pendiente' => $ultimaMembresia->precio_total,
                'fecha_compra' => now(),
                'fecha_vencimiento' => now()->addDays($ultimaMembresia->tipoMembresia->duracion_dias),
                'visitas_permitidas' => $ultimaMembresia->visitas_permitidas,
                'visitas_restantes' => $ultimaMembresia->visitas_permitidas,
                'renovacion' => true
            ]);
            
            // Crear pago inicial con los mismos datos que el último pago
            $ultimoPago = Pago::where('id_membresia', $ultimaMembresia->id_membresia)
                ->orderBy('fecha_pago', 'desc')
                ->first();
                
            if ($ultimoPago) {
                Pago::create([
                    'id_membresia' => $nuevaMembresia->id_membresia,
                    'id_usuario' => $id_usuario,
                    'monto' => $ultimoPago->monto,
                    'fecha_pago' => now(),
                    'estado' => 'pendiente',
                    'id_metodo_pago' => $ultimoPago->id_metodo_pago
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Membresía renovada exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al renovar la membresía: ' . $e->getMessage());
        }
    }
} 