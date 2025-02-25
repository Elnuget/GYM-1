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
        $user = auth()->user();
        $cliente = Cliente::where('user_id', $user->id)->firstOrFail();

        // Obtener datos para el dashboard
        $datos = [
            'proxima_sesion' => 'Por definir',
            'asistencias_mes' => 0,
            'membresia' => null,
            'medidas' => null,
            'objetivos' => null,
            'rutina_actual' => null
        ];

        // Obtener asistencias del mes actual
        $datos['asistencias_mes'] = Asistencia::where('user_id', $user->id)
            ->whereMonth('fecha_asistencia', Carbon::now()->month)
            ->where('estado', 'presente')
            ->count();

        // Obtener membresía activa
        $membresia = Membresia::where('id_usuario', $user->id)
            ->where('fecha_vencimiento', '>=', now())
            ->first();

        if ($membresia) {
            $datos['membresia'] = [
                'tipo' => $membresia->tipo_membresia,
                'fecha_fin' => $membresia->fecha_vencimiento->format('d/m/Y'),
                'estado' => 'activa'
            ];
        }

        // Obtener últimas medidas
        $ultimasMedidas = MedidaCorporal::where('cliente_id', $cliente->id_cliente)
            ->latest('fecha_medicion')
            ->first();

        if ($ultimasMedidas) {
            $datos['medidas'] = [
                'peso' => $ultimasMedidas->peso,
                'altura' => $ultimasMedidas->altura,
                'imc' => round($ultimasMedidas->peso / pow($ultimasMedidas->altura / 100, 2), 2),
                'fecha_medicion' => $ultimasMedidas->fecha_medicion->format('d/m/Y')
            ];
        }

        // Obtener objetivos activos
        $objetivos = ObjetivoCliente::where('cliente_id', $cliente->id_cliente)
            ->where('activo', true)
            ->first();

        if ($objetivos) {
            $datos['objetivos'] = [
                'principal' => ucfirst(str_replace('_', ' ', $objetivos->objetivo_principal)),
                'nivel' => ucfirst($objetivos->nivel_experiencia),
                'dias_entrenamiento' => $objetivos->dias_entrenamiento
            ];
        }

        // Obtener rutina actual
        $rutinaActual = RutinaCliente::where('cliente_id', $cliente->id_cliente)
            ->where('estado', 'activa')
            ->with('rutina')
            ->first();

        if ($rutinaActual) {
            $datos['rutina_actual'] = [
                'nombre' => $rutinaActual->rutina->nombre_rutina,
                'objetivo' => $rutinaActual->rutina->objetivo,
                'progreso' => $rutinaActual->progreso,
                'fecha_inicio' => $rutinaActual->fecha_inicio->format('d/m/Y'),
                'id_rutina' => $rutinaActual->id_rutina_cliente
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