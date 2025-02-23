<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOnboardingCompletion
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if ($user && $user->hasRole('cliente')) {
            $onboarding = $user->cliente->onboardingProgress;
            
            // Si el onboarding no está completo y no estamos en una ruta de onboarding
            if (!$this->isOnboardingComplete($onboarding) && !$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.perfil');
            }
        }

        return $next($request);
    }

    private function isOnboardingComplete($onboarding)
    {
        return $onboarding &&
               $onboarding->perfil_completado &&
               $onboarding->medidas_iniciales &&
               $onboarding->objetivos_definidos &&
               $onboarding->tutorial_visto;
    }
} 