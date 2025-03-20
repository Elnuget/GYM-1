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
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->orderBy('id_membresia', 'desc');
            
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
        $usuarios = User::with(['cliente.gimnasio'])->get();
        $tiposMembresia = TipoMembresia::where('estado', 1)->get();
        $metodosPago = MetodoPago::all();
        
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
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        // Obtener nombre del usuario si existe
        $usuarioSeleccionado = null;
        if ($idUsuario) {
            $usuarioSeleccionado = User::find($idUsuario);
        }
        
        // Calcular membresías vencidas en el mes actual (desde el primer día hasta la fecha actual)
        $fechaActual = now();
        $primerDiaMes = $fechaActual->copy()->startOfMonth();
        
        // Membresías vencidas en el mes actual (con fecha de vencimiento anterior a hoy pero dentro del mes actual)
        $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
        
        // Total de membresías activas este mes (con fecha de vencimiento en el mes actual)
        $totalMembresiasActivas = Membresia::whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
        
        // Calcular porcentaje
        $porcentajeVencidas = $totalMembresiasActivas > 0 
            ? round(($membresiasVencidasMes / $totalMembresiasActivas) * 100) 
            : 0;
        
        // Membresías activas (con fecha de vencimiento mayor o igual a la fecha actual)
        $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
        
        // Cálculo de saldos pendientes
        $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
        $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
        
        // Membresías vencidas sin renovar
        // Ahora buscamos usuarios cuya última membresía ha vencido en un mes anterior al actual
        $membresiasNoRenovadas = 0;
        
        // Obtener todos los usuarios que tienen membresías
        $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            // Obtener la membresía más reciente de este usuario (por fecha de vencimiento)
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            // Verificar si la última membresía del usuario ha vencido en un mes anterior al actual
            if ($ultimaMembresia && 
                $ultimaMembresia->fecha_vencimiento && 
                $ultimaMembresia->fecha_vencimiento < $fechaActual &&
                ($ultimaMembresia->fecha_vencimiento->month != $fechaActual->month || 
                 $ultimaMembresia->fecha_vencimiento->year != $fechaActual->year)) {
                $membresiasNoRenovadas++;
            }
        }
        
        // Ya no necesitamos calcular tasa de no renovación pues eliminamos la barra de porcentaje
        
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
            'porcentajeVencidas',
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
            $pago = new Pago();
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
    }

    public function destroy(Membresia $membresia)
    {
        $membresia->delete();

        return redirect()->route('membresias.index')
            ->with('success', 'Membresía eliminada exitosamente');
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
        // Obtener fecha actual
        $fechaActual = now();
        
        // Consultar membresías vencidas en el mes actual
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->orderBy('fecha_vencimiento', 'desc');
            
        $membresias = $query->get();
        
        // Obtener datos necesarios para la vista
        $usuarios = User::with(['cliente.gimnasio'])->get();
        $tiposMembresia = TipoMembresia::where('estado', 1)->get();
        $metodosPago = MetodoPago::all();
        
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
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarVencidas = true;
        $idUsuario = null;
        $mostrarTodos = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Membresías vencidas en el mes actual
        $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
        // Membresías activas (con fecha de vencimiento mayor o igual a la fecha actual)
        $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
        
        // Cálculo de saldos pendientes
        $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
        $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
        
        // Membresías vencidas sin renovar
        // Ahora buscamos usuarios cuya última membresía ha vencido en un mes anterior al actual
        $membresiasNoRenovadas = 0;
        
        // Obtener todos los usuarios que tienen membresías
        $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            // Obtener la membresía más reciente de este usuario (por fecha de vencimiento)
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            // Verificar si la última membresía del usuario ha vencido en un mes anterior al actual
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
        // Obtener fecha actual
        $fechaActual = now();
        
        // Obtener todos los usuarios que tienen membresías
        $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
        $usuariosSinRenovar = [];
        
        foreach ($usuariosConMembresias as $idUsuarioMem) {
            // Obtener la membresía más reciente de este usuario (por fecha de vencimiento)
            $ultimaMembresia = Membresia::where('id_usuario', $idUsuarioMem)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();
            
            // Verificar si la última membresía del usuario ha vencido en un mes anterior al actual
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
            ->whereIn('id_membresia', $usuariosSinRenovar)
            ->orderBy('fecha_vencimiento', 'desc');
            
        $membresias = $query->get();
        
        // Obtener datos necesarios para la vista
        $usuarios = User::with(['cliente.gimnasio'])->get();
        $tiposMembresia = TipoMembresia::where('estado', 1)->get();
        $metodosPago = MetodoPago::all();
        
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
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarSinRenovar = true; // Variable para indicar que estamos mostrando las membresías sin renovar
        $idUsuario = null;
        $mostrarTodos = false;
        $mostrarVencidas = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
        // Membresías sin renovar (reutilizamos el cálculo anterior)
        $membresiasNoRenovadas = count($usuariosSinRenovar);
        
        // Membresías activas (con fecha de vencimiento mayor o igual a la fecha actual)
        $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
        
        // Cálculo de saldos pendientes
        $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
        $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
        
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
     * Mostrar solo las membresías activas.
     */
    public function activas()
    {
        // Obtener fecha actual
        $fechaActual = now();
        
        // Consultar membresías activas
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->whereDate('fecha_vencimiento', '>=', $fechaActual)
            ->orderBy('fecha_vencimiento', 'asc'); // Ordenadas por fecha de vencimiento ascendente
            
        $membresias = $query->get();
        
        // Obtener datos necesarios para la vista
        $usuarios = User::with(['cliente.gimnasio'])->get();
        $tiposMembresia = TipoMembresia::where('estado', 1)->get();
        $metodosPago = MetodoPago::all();
        
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
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarActivas = true; // Variable para indicar que estamos mostrando las membresías activas
        $idUsuario = null;
        $mostrarTodos = false;
        $mostrarVencidas = false;
        $mostrarSinRenovar = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
        // Membresías activas
        $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
        
        // Cálculo de saldos pendientes
        $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
        $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
        
        // Membresías sin renovar
        $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
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
        // Obtener fecha actual
        $fechaActual = now();
        
        // Consultar membresías con saldo pendiente
        $query = Membresia::with(['usuario', 'tipoMembresia.gimnasio'])
            ->where('saldo_pendiente', '>', 0)
            ->orderBy('saldo_pendiente', 'desc'); // Ordenadas por monto pendiente descendente
            
        $membresias = $query->get();
        
        // Obtener datos necesarios para la vista
        $usuarios = User::with(['cliente.gimnasio'])->get();
        $tiposMembresia = TipoMembresia::where('estado', 1)->get();
        $metodosPago = MetodoPago::all();
        
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
        $anioActual = date('Y');
        $anios = range($anioActual - 2, $anioActual + 3);
        
        $mes = $fechaActual->month;
        $anio = $fechaActual->year;
        $mostrarSaldosPendientes = true; // Variable para indicar que estamos mostrando membresías con saldo pendiente
        $idUsuario = null;
        $mostrarTodos = false;
        $mostrarVencidas = false;
        $mostrarSinRenovar = false;
        $mostrarActivas = false;
        $tipoFiltro = 'vencimiento';
        $usuarioSeleccionado = null;
        
        // Calcular estadísticas
        $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
            
        // Membresías activas
        $membresiasActivas = Membresia::whereDate('fecha_vencimiento', '>=', $fechaActual)->count();
        
        // Cálculo de saldos pendientes
        $totalSaldosPendientes = Membresia::where('saldo_pendiente', '>', 0)->sum('saldo_pendiente');
        $membresiasPendientesPago = Membresia::where('saldo_pendiente', '>', 0)->count();
        
        // Membresías sin renovar
        $usuariosConMembresias = Membresia::distinct('id_usuario')->pluck('id_usuario');
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
} 