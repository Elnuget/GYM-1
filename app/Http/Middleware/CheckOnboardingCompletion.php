<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboardingCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && $user->hasRole('cliente')) {
            $cliente = $user->cliente;
            
            if (!$cliente || !$this->isOnboardingComplete($cliente->onboardingProgress)) {
                if (!$request->routeIs('onboarding.*')) {
                    return redirect()->route('onboarding.perfil');
                }
            }
        }

        return $next($request);
    }

    private function isOnboardingComplete($onboardingProgress)
    {
        if (!$onboardingProgress) {
            return false;
        }

        return $onboardingProgress->perfil_completado &&
               $onboardingProgress->medidas_iniciales &&
               $onboardingProgress->objetivos_definidos &&
               $onboardingProgress->tutorial_visto;
    }
}
