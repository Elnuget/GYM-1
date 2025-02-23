<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\OnboardingProgress;
use App\Models\Asistencia;
use App\Models\Membresia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Quitamos el middleware del constructor
    }

    public function index()
    {
        $user = auth()->user();
        $cliente = Cliente::where('user_id', $user->id)->first();

        // Si no existe el cliente, lo creamos junto con su onboarding
        if (!$cliente) {
            $cliente = Cliente::create([
                'user_id' => $user->id,
                'gimnasio_id' => session('gimnasio_id'),
            ]);

            OnboardingProgress::create([
                'cliente_id' => $cliente->id_cliente,
                'perfil_completado' => false,
                'medidas_iniciales' => false,
                'objetivos_definidos' => false,
                'tutorial_visto' => false
            ]);

            return redirect()->route('onboarding.perfil');
        }

        // Verificar onboarding
        $onboarding = $cliente->onboardingProgress;
        if (!$onboarding || !$this->onboardingCompleto($onboarding)) {
            return $this->redirigirOnboarding($onboarding);
        }

        // Obtener datos para el dashboard
        $datos = [
            'proxima_sesion' => 'Por definir',
            'asistencias_mes' => 0,
            'membresia' => null
        ];

        // Obtener asistencias del mes actual
        $asistencias = Asistencia::where('user_id', $user->id)
            ->whereMonth('fecha_asistencia', Carbon::now()->month)
            ->where('estado', 'presente')
            ->count();
        $datos['asistencias_mes'] = $asistencias;

        // Obtener membresía activa
        $membresia = Membresia::where('id_usuario', $user->id)
            ->where('fecha_vencimiento', '>=', now())
            ->first();

        if ($membresia) {
            $datos['membresia'] = [
                'tipo' => $membresia->tipo_membresia,
                'fecha_fin' => $membresia->fecha_vencimiento->format('d/m/Y'),
                'estado' => $membresia->fecha_vencimiento >= now() ? 'activa' : 'vencida'
            ];
        } else {
            // Obtener membresía por defecto del gimnasio
            $membresiaPorDefecto = $cliente->gimnasio->membresia_default;
            $datos['membresia'] = [
                'tipo' => $membresiaPorDefecto ? $membresiaPorDefecto->tipo_membresia : 'Por definir',
                'fecha_fin' => 'Pendiente de activación',
                'estado' => 'inactiva'
            ];
        }

        return view('cliente.dashboard.index', [
            'cliente' => $cliente,
            'datos' => $datos
        ]);
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