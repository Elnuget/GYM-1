<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\AsignacionRutina;
use App\Models\Nutricion;
use App\Models\Membresia;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Quitamos el middleware del constructor
    }

    public function index()
    {
        $user = Auth::user();
        $cliente = Cliente::where('user_id', $user->id)->first();
        
        // Verificar si el cliente existe
        if (!$cliente) {
            return redirect()->route('completar.registro.cliente.form')
                ->with('error', 'Por favor, completa tu registro como cliente.');
        }
        
        // Obtener asistencia actual (si hay una entrada sin salida)
        $asistenciaActual = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->whereDate('fecha', Carbon::today())
            ->whereNull('hora_salida')
            ->first();
            
        // Contar asistencias del mes
        $asistenciasMes = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->whereMonth('fecha', Carbon::now()->month)
            ->whereYear('fecha', Carbon::now()->year)
            ->count();
            
        // Obtener última asistencia
        $ultimaAsistencia = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->first();
            
        // Obtener rutina actual
        $rutinaActual = AsignacionRutina::where('user_id', $user->id)
            ->orderBy('fecha_asignacion', 'desc')
            ->with('rutina')
            ->first();
            
        // Obtener plan nutricional
        $planNutricional = Nutricion::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activo')
            ->latest('fecha_asignacion')
            ->first();
            
        // Obtener membresía actual (corregido para usar id_usuario en lugar de cliente_id)
        // y para no usar la columna estado que no existe
        $membresia = Membresia::where('id_usuario', $user->id)
            ->where('fecha_vencimiento', '>=', Carbon::now())
            ->orderBy('fecha_vencimiento', 'desc')
            ->first();
            
        return view('cliente.dashboard.index', compact(
            'asistenciaActual',
            'asistenciasMes',
            'ultimaAsistencia',
            'rutinaActual',
            'planNutricional',
            'membresia'
        ));
    }

    private function onboardingCompleto($onboarding)
    {
        return $onboarding &&
               $onboarding->perfil_completado &&
               $onboarding->medidas_iniciales &&
               $onboarding->objetivos_definidos &&
               $onboarding->tutorial_visto;
    }

    private function redirigirOnboarding($onboarding)
    {
        if (!$onboarding) {
            return redirect()->route('onboarding.perfil');
        }
        if (!$onboarding->perfil_completado) {
            return redirect()->route('onboarding.perfil');
        }
        if (!$onboarding->medidas_iniciales) {
            return redirect()->route('onboarding.medidas');
        }
        if (!$onboarding->objetivos_definidos) {
            return redirect()->route('onboarding.objetivos');
        }
        if (!$onboarding->tutorial_visto) {
            return redirect()->route('onboarding.tour');
        }
    }
} 