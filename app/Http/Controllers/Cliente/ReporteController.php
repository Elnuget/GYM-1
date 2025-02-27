<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\MedidaCorporal;
use App\Models\Asistencia;
use App\Models\RutinaCliente;
use App\Models\RutinaPredefinida;
use App\Models\Ejercicio;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProgresoExport;

class ReporteController extends Controller
{
    public function index()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        // Datos para el gráfico de medidas corporales
        $medidas = MedidaCorporal::where('cliente_id', $cliente->id_cliente)
            ->orderBy('fecha_medicion')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->fecha_medicion)->format('Y-m');
            });

        // Preparar datos para gráficas de medidas
        $datosMedidas = [
            'labels' => [],
            'peso' => [],
            'cintura' => [],
            'pecho' => [],
            'brazos' => []
        ];

        foreach ($medidas as $mes => $grupo) {
            $datosMedidas['labels'][] = Carbon::createFromFormat('Y-m', $mes)->format('M Y');
            $datosMedidas['peso'][] = $grupo->avg('peso');
            $datosMedidas['cintura'][] = $grupo->avg('cintura');
            $datosMedidas['pecho'][] = $grupo->avg('pecho');
            $datosMedidas['brazos'][] = ($grupo->avg('biceps_derecho') + $grupo->avg('biceps_izquierdo')) / 2;
        }

        // Datos para el gráfico de asistencias
        $periodo = CarbonPeriod::create(now()->subMonths(6), '1 month', now());
        $asistencias = [];
        
        foreach ($periodo as $fecha) {
            $mes = $fecha->format('Y-m');
            $count = Asistencia::where('cliente_id', $cliente->id_cliente)
                ->whereYear('created_at', $fecha->year)
                ->whereMonth('created_at', $fecha->month)
                ->count();
            
            $asistencias[] = [
                'mes' => $fecha->format('M Y'),
                'total' => $count
            ];
        }

        // Datos para el progreso de rutinas
        $rutinas = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->with(['rutinaPredefinida', 'ejercicios'])
            ->orderBy('fecha_inicio', 'desc')
            ->take(5)
            ->get();

        $datosRutinas = [
            'labels' => [],
            'completadas' => [],
            'total' => []
        ];

        foreach ($rutinas as $rutina) {
            $datosRutinas['labels'][] = $rutina->rutinaPredefinida->nombre;
            $datosRutinas['completadas'][] = $rutina->ejercicios()
                ->where('completado', true)
                ->count();
            $datosRutinas['total'][] = $rutina->ejercicios()->count();
        }

        // Calcular estadísticas generales
        $estadisticas = [
            'total_asistencias' => Asistencia::where('cliente_id', $cliente->id_cliente)->count(),
            'promedio_mensual' => round(collect($asistencias)->avg('total'), 1),
            'dias_consecutivos' => $this->calcularDiasConsecutivos($cliente->id_cliente),
            'rutinas_completadas' => RutinaCliente::where('cliente_id', $cliente->id_cliente)
                ->where('estado', 'completada')
                ->count(),
            'cambio_peso' => $this->calcularCambioPeso($cliente->id_cliente),
            'tiempo_total' => Asistencia::where('cliente_id', $cliente->id_cliente)
                ->whereNotNull('hora_salida')
                ->get()
                ->sum(function($asistencia) {
                    try {
                        if (!$asistencia->fecha_asistencia || !$asistencia->hora_ingreso || !$asistencia->hora_salida) {
                            return 0;
                        }
                        $entrada = Carbon::parse($asistencia->fecha_asistencia . ' ' . $asistencia->hora_ingreso);
                        $salida = Carbon::parse($asistencia->fecha_asistencia . ' ' . $asistencia->hora_salida);
                        return $entrada->diffInMinutes($salida);
                    } catch (\Exception $e) {
                        return 0;
                    }
                })
        ];

        return view('cliente.reportes.index', compact(
            'datosMedidas',
            'asistencias',
            'datosRutinas',
            'estadisticas'
        ));
    }

    private function calcularDiasConsecutivos($clienteId)
    {
        $asistencias = Asistencia::where('cliente_id', $clienteId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('created_at')
            ->map(function ($fecha) {
                return Carbon::parse($fecha)->format('Y-m-d');
            });

        if ($asistencias->isEmpty()) {
            return 0;
        }

        $diasConsecutivos = 1;
        $ultimaFecha = Carbon::parse($asistencias->first());

        foreach ($asistencias->skip(1) as $fecha) {
            $fecha = Carbon::parse($fecha);
            if ($ultimaFecha->subDay()->format('Y-m-d') == $fecha->format('Y-m-d')) {
                $diasConsecutivos++;
                $ultimaFecha = $fecha;
            } else {
                break;
            }
        }

        return $diasConsecutivos;
    }

    private function calcularCambioPeso($clienteId)
    {
        $primeramedida = MedidaCorporal::where('cliente_id', $clienteId)
            ->orderBy('fecha_medicion')
            ->first();

        $ultimamedida = MedidaCorporal::where('cliente_id', $clienteId)
            ->orderBy('fecha_medicion', 'desc')
            ->first();

        if (!$primeramedida || !$ultimamedida) {
            return 0;
        }

        return $ultimamedida->peso - $primeramedida->peso;
    }

    public function exportarPDF()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        // Obtener los mismos datos que en el index
        $medidas = MedidaCorporal::where('cliente_id', $cliente->id_cliente)
            ->orderBy('fecha_medicion')
            ->get();
        
        // Corregimos el orderBy para usar created_at en lugar de fecha_asistencia
        $asistencias = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $rutinas = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->with(['rutinaPredefinida', 'ejercicios'])
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // Preparar datos adicionales para el PDF
        $estadisticas = [
            'total_asistencias' => Asistencia::where('cliente_id', $cliente->id_cliente)->count(),
            'tiempo_total' => Asistencia::where('cliente_id', $cliente->id_cliente)
                ->whereNotNull('hora_salida')
                ->get()
                ->sum(function($asistencia) {
                    try {
                        if (!$asistencia->fecha_asistencia || !$asistencia->hora_ingreso || !$asistencia->hora_salida) {
                            return 0;
                        }
                        $entrada = Carbon::parse($asistencia->fecha_asistencia . ' ' . $asistencia->hora_ingreso);
                        $salida = Carbon::parse($asistencia->fecha_asistencia . ' ' . $asistencia->hora_salida);
                        return $entrada->diffInMinutes($salida);
                    } catch (\Exception $e) {
                        return 0;
                    }
                }),
            'cambio_peso' => $this->calcularCambioPeso($cliente->id_cliente)
        ];

        // Generar PDF usando la vista
        $pdf = PDF::loadView('cliente.reportes.pdf', compact(
            'cliente',
            'medidas',
            'asistencias',
            'rutinas',
            'estadisticas'
        ));

        // Descargar el PDF
        return $pdf->download('reporte-progreso.pdf');
    }

    public function exportarExcel()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        return Excel::download(
            new ProgresoExport($cliente),
            'reporte-progreso-' . now()->format('d-m-Y') . '.xlsx'
        );
    }
} 