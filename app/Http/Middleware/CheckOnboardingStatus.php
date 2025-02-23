<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOnboardingStatus
{
    public function handle(Request $request, Closure $next)
    {
        $onboarding = auth()->user()->cliente->onboardingProgress;

        if (!$onboarding->tutorial_completado) {
            $nextStep = $this->getNextStep($onboarding);
            return redirect()->route("onboarding.$nextStep");
        }

        return $next($request);
    }

    private function getNextStep($onboarding)
    {
        if (!$onboarding->perfil_completado) return 'perfil';
        if (!$onboarding->medidas_iniciales) return 'medidas';
        if (!$onboarding->objetivos_definidos) return 'objetivos';
        if (!$onboarding->tutorial_visto) return 'tour';
        
        return 'completado';
    }
} 