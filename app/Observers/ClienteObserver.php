<?php

namespace App\Observers;

use App\Models\Cliente;
use App\Models\OnboardingProgress;

class ClienteObserver
{
    public function created(Cliente $cliente)
    {
        // Crear registro de onboarding al crear un nuevo cliente
        OnboardingProgress::create([
            'cliente_id' => $cliente->id_cliente,
            'perfil_completado' => false,
            'medidas_iniciales' => false,
            'objetivos_definidos' => false,
            'tutorial_visto' => false
        ]);
    }
} 