<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\User;
use App\Models\TipoMembresia;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

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
        
        // Membresías vencidas sin renovar
        // Aquí buscamos membresías vencidas que no tienen otra membresía del mismo tipo para el mismo usuario
        // con fecha posterior
        $membresiasNoRenovadas = 0;
        $membresiasVencidas = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)->get();
        
        foreach ($membresiasVencidas as $membresia) {
            // Verificar si existe una membresía posterior del mismo tipo para el mismo usuario
            $tieneRenovacion = Membresia::where('id_usuario', $membresia->id_usuario)
                ->where('id_tipo_membresia', $membresia->id_tipo_membresia)
                ->whereDate('fecha_vencimiento', '>', $membresia->fecha_vencimiento)
                ->exists();
            
            if (!$tieneRenovacion) {
                $membresiasNoRenovadas++;
            }
        }
        
        // Calcular tasa de no renovación
        $tasaNoRenovacion = $membresiasVencidas->count() > 0 
            ? round(($membresiasNoRenovadas / $membresiasVencidas->count()) * 100) 
            : 0;
        
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
            'tasaNoRenovacion'
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

        Membresia::create($validated);

        return redirect()->route('membresias.index')
            ->with('success', 'Membresía creada exitosamente');
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
        
        // Calcular el resto de estadísticas como en el método index
        $fechaActual = now();
        $primerDiaMes = $fechaActual->copy()->startOfMonth();
        
        // Membresías vencidas en el mes actual
        $membresiasVencidasMes = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)
            ->whereMonth('fecha_vencimiento', $fechaActual->month)
            ->whereYear('fecha_vencimiento', $fechaActual->year)
            ->count();
        
        // Membresías sin renovar
        $membresiasNoRenovadas = 0;
        $membresiasVencidasTotal = Membresia::whereDate('fecha_vencimiento', '<', $fechaActual)->get();
        
        foreach ($membresiasVencidasTotal as $membresia) {
            $tieneRenovacion = Membresia::where('id_usuario', $membresia->id_usuario)
                ->where('id_tipo_membresia', $membresia->id_tipo_membresia)
                ->whereDate('fecha_vencimiento', '>', $membresia->fecha_vencimiento)
                ->exists();
            
            if (!$tieneRenovacion) {
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
            'usuarioSeleccionado'
        ));
    }
} 