<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Membresia;
use App\Models\MetodoPago;
use App\Models\User;
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
        
        // Crear query base con relaciones necesarias
        $query = Pago::with([
            'membresia.tipoMembresia', 
            'usuario', 
            'metodoPago'
        ]);
        
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
        
        // Ejecutar la consulta ordenando por fecha de pago descendente - sin paginación
        $pagos = $query->orderBy('fecha_pago', 'desc')->get();
        
        // Obtener el total de pagos
        $totalPagos = $pagos->count();
        
        // Estadísticas de pagos
        $estadisticasQuery = DB::table('pagos')
            ->join('metodos_pago', 'pagos.id_metodo_pago', '=', 'metodos_pago.id_metodo_pago')
            ->select(
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
        
        // Datos para los selectores de filtro
        $usuarios = User::all();
        $membresias = Membresia::with('tipoMembresia', 'usuario')->get();
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
        $request->validate([
            'id_membresia' => 'required|exists:membresias,id_membresia',
            'id_usuario' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'estado_pago' => 'required|in:pendiente,aprobado,rechazado',
            'notas' => 'nullable|string|max:255',
            'fecha_pago' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            $pago = new Pago();
            $pago->id_membresia = $request->id_membresia;
            $pago->id_usuario = $request->id_usuario;
            $pago->monto = $request->monto;
            $pago->id_metodo_pago = $request->id_metodo_pago;
            $pago->estado = $request->estado_pago;
            $pago->notas = $request->notas;
            $pago->fecha_pago = $request->fecha_pago ?? now();
            
            // Si el estado es aprobado, establecer la fecha de aprobación
            if ($pago->estado === 'aprobado') {
                $pago->fecha_aprobacion = now();
            }
            
            // Guardar el pago
            $pago->save();
            
            // Si el estado es aprobado, actualizar el saldo pendiente de la membresía
            if ($pago->estado === 'aprobado') {
                $membresia = Membresia::findOrFail($request->id_membresia);
                $nuevoSaldo = $membresia->saldo_pendiente - $pago->monto;
                
                // Asegurarse de que el saldo no sea negativo
                $membresia->saldo_pendiente = max(0, $nuevoSaldo);
                $membresia->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pago registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el pago: ' . $e->getMessage());
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
        $validated = $request->validate([
            'id_membresia' => 'required|exists:membresias,id_membresia',
            'id_usuario' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB máx
            'notas' => 'nullable|string',
            'fecha_aprobacion' => 'nullable|date'
        ]);

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

        $pago->update($validated);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado exitosamente');
    }

    /**
     * Approve a pending payment.
     */
    public function aprobar(Pago $pago)
    {
        if ($pago->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo se pueden aprobar pagos pendientes');
        }

        DB::beginTransaction();
        try {
            // Actualizar el pago
            $pago->estado = 'aprobado';
            $pago->fecha_aprobacion = now();
            $pago->save();

            // Actualizar el saldo pendiente de la membresía
            $membresia = $pago->membresia;
            $nuevoSaldo = $membresia->saldo_pendiente - $pago->monto;
            
            // Asegurarse de que el saldo no sea negativo
            $membresia->saldo_pendiente = max(0, $nuevoSaldo);
            $membresia->save();

            DB::commit();
            return redirect()->back()->with('success', 'Pago aprobado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al aprobar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Pago $pago)
    {
        if ($pago->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo se pueden eliminar pagos pendientes');
        }

        try {
            $pago->delete();
            return redirect()->back()->with('success', 'Pago eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }
}