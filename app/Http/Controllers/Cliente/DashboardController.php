<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\OnboardingProgress;
use App\Models\Asistencia;
use App\Models\Membresia;
use App\Models\MedidaCorporal;
use App\Models\ObjetivoCliente;
use App\Models\RutinaCliente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Quitamos el middleware del constructor
    }

    public function index()
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();

        // Obtener membresía activa - ajustado a la estructura actual de la BD
        $membresia = Membresia::where('id_usuario', auth()->id())
            ->where('fecha_vencimiento', '>=', Carbon::now())
            ->first();

        // Obtener estadísticas de asistencia
        $asistenciasMes = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->whereMonth('fecha', Carbon::now()->month)
            ->where('estado', 'completada')
            ->count();

        // Obtener última asistencia
        $ultimaAsistencia = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'completada')
            ->latest('fecha')
            ->first();

        // Obtener asistencia actual si existe
        $asistenciaActual = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->whereDate('fecha', Carbon::today())
            ->first();

        // Obtener rutina actual
        $rutinaActual = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->with('rutina')
            ->first();

        // Obtener plan nutricional activo (cuando esté implementado)
        $planNutricional = null; // Por ahora es null hasta que se implemente

        return view('cliente.dashboard.index', compact(
            'asistenciasMes',
            'ultimaAsistencia',
            'asistenciaActual',
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