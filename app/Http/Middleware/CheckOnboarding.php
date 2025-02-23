<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && $user->rol === 'cliente') {
            $onboarding = $user->cliente->onboardingProgress;
            
            // Si el onboarding no está completo, redirigir al paso correspondiente
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

        return $next($request);
    }
} 