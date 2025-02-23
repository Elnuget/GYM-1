<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\OnboardingProgress;

class OnboardingController extends Controller
{
    public function bienvenida()
    {
        return view('cliente.onboarding.bienvenida', [
            'progreso' => $this->calcularProgreso()
        ]);
    }

    public function perfil()
    {
        $cliente = auth()->user()->cliente;
        return view('cliente.onboarding.perfil', compact('cliente'));
    }

    public function storePerfil(Request $request)
    {
        $request->validate([
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string',
            'genero' => 'required|in:M,F,O',
            'ocupacion' => 'required|string'
        ]);

        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        $cliente->update([
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'genero' => $request->genero,
            'ocupacion' => $request->ocupacion
        ]);

        $onboarding = OnboardingProgress::where('cliente_id', $cliente->id_cliente)->firstOrFail();
        $onboarding->update([
            'perfil_completado' => true
        ]);

        return redirect()->route('onboarding.medidas');
    }

    public function medidas()
    {
        return view('cliente.onboarding.medidas');
    }

    public function objetivos()
    {
        return view('cliente.onboarding.objetivos');
    }

    public function tour()
    {
        return view('cliente.onboarding.tour');
    }

    public function storeMedidas(Request $request)
    {
        $request->validate([
            'peso' => 'required|numeric|min:20|max:300',
            'altura' => 'required|numeric|min:100|max:250',
            'cintura' => 'nullable|numeric|min:40|max:200',
            'cadera' => 'nullable|numeric|min:40|max:200',
        ]);

        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        // Guardar medidas en una nueva tabla o en un campo JSON
        $medidas = [
            'peso' => $request->peso,
            'altura' => $request->altura,
            'cintura' => $request->cintura ?: null,
            'cadera' => $request->cadera ?: null,
            'fecha' => now()
        ];

        // Actualizar el progreso del onboarding
        $onboarding = OnboardingProgress::where('cliente_id', $cliente->id_cliente)->firstOrFail();
        $onboarding->update([
            'medidas_iniciales' => true
        ]);

        return redirect()->route('onboarding.objetivos');
    }

    public function storeObjetivos(Request $request)
    {
        $request->validate([
            'objetivo_principal' => 'required|in:perdida_peso,ganancia_muscular,mantenimiento',
            'nivel_experiencia' => 'required|in:principiante,intermedio,avanzado',
            'dias_entrenamiento' => 'required|in:2-3,3-4,4-5,6+',
            'condiciones_medicas' => 'nullable|string|max:500',
        ]);

        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        // Guardar objetivos
        $objetivos = [
            'objetivo_principal' => $request->objetivo_principal,
            'nivel_experiencia' => $request->nivel_experiencia,
            'dias_entrenamiento' => $request->dias_entrenamiento,
            'condiciones_medicas' => $request->condiciones_medicas,
            'fecha' => now()
        ];

        // Actualizar el progreso del onboarding
        $onboarding = OnboardingProgress::where('cliente_id', $cliente->id_cliente)->firstOrFail();
        $onboarding->update([
            'objetivos_definidos' => true
        ]);

        return redirect()->route('onboarding.tour');
    }

    public function completeTour(Request $request)
    {
        $cliente = Cliente::where('user_id', auth()->id())->firstOrFail();
        
        // Actualizar el progreso del onboarding
        $onboarding = OnboardingProgress::where('cliente_id', $cliente->id_cliente)->firstOrFail();
        $onboarding->update([
            'tutorial_visto' => true
        ]);

        return redirect()->route('cliente.dashboard');
    }

    private function calcularProgreso()
    {
        $onboarding = auth()->user()->cliente->onboardingProgress;
        $pasos_completados = collect([
            $onboarding->perfil_completado,
            $onboarding->medidas_iniciales,
            $onboarding->objetivos_definidos,
            $onboarding->tutorial_visto
        ])->filter()->count();

        return ($pasos_completados / 4) * 100;
    }
} 