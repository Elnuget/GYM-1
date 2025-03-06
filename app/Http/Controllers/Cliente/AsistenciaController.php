<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        // Configurar zona horaria para todas las instancias de Carbon en este controlador
        Carbon::setLocale('es');
        date_default_timezone_set('America/Guayaquil');
    }

    public function index()
    {
        $user = Auth::user();
        $cliente = Cliente::where('user_id', $user->id)->first();

        // Verificar si el cliente existe
        if (!$cliente) {
            // Redirigir al usuario para completar su registro como cliente
            return redirect()->route('completar.registro.cliente.form')
                ->with('error', 'Por favor, completa tu registro como cliente para acceder a esta sección.');
        }

        $asistenciaActual = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->whereDate('fecha', Carbon::today())
            ->whereNull('hora_salida')
            ->first();

        $asistencias = Asistencia::where('cliente_id', $cliente->id_cliente)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->paginate(10);

        return view('cliente.asistencias.index', compact('asistenciaActual', 'asistencias'));
    }

    public function registrarEntrada()
    {
        try {
            $user = Auth::user();
            $cliente = Cliente::where('user_id', $user->id)->first();

            // Verificar si el cliente existe
            if (!$cliente) {
                return redirect()->route('completar.registro.cliente.form')
                    ->with('error', 'Por favor, completa tu registro como cliente para registrar asistencias.');
            }

            // Verificar si ya existe una entrada sin salida
            $asistenciaActiva = Asistencia::where('cliente_id', $cliente->id_cliente)
                ->whereDate('fecha', Carbon::today())
                ->whereNull('hora_salida')
                ->first();

            if ($asistenciaActiva) {
                return back()->with('error', 'Ya tienes una asistencia activa hoy.');
            }

            // Crear nueva asistencia usando la zona horaria correcta
            Asistencia::create([
                'cliente_id' => $cliente->id_cliente,
                'fecha' => Carbon::now(),
                'hora_entrada' => Carbon::now()->format('H:i:s'),
                'estado' => 'activa'
            ]);

            return back()->with('success', 'Entrada registrada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar la entrada: ' . $e->getMessage());
        }
    }

    public function registrarSalida(Asistencia $asistencia)
    {
        try {
            $user = Auth::user();
            $cliente = Cliente::where('user_id', $user->id)->first();

            // Verificar si el cliente existe
            if (!$cliente) {
                return redirect()->route('completar.registro.cliente.form')
                    ->with('error', 'Por favor, completa tu registro como cliente para registrar asistencias.');
            }

            // Verificar que la asistencia pertenezca al cliente
            if ($asistencia->cliente_id != $cliente->id_cliente) {
                return back()->with('error', 'No tienes permiso para registrar esta salida.');
            }

            if ($asistencia->hora_salida) {
                return back()->with('error', 'Esta asistencia ya tiene registrada la salida.');
            }

            // Registrar la salida usando la zona horaria correcta
            $asistencia->hora_salida = Carbon::now()->format('H:i:s');
            $asistencia->duracion_minutos = $asistencia->calcularDuracion();
            $asistencia->estado = 'completada';
            $asistencia->save();

            return back()->with('success', 'Salida registrada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }
} 