<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!auth()->user()->hasRole($role)) {
            // Si es cliente, redirigir a su dashboard
            if (auth()->user()->hasRole('cliente')) {
                return redirect()->route('cliente.dashboard');
            }
            // Si es entrenador, redirigir a su dashboard
            if (auth()->user()->hasRole('entrenador')) {
                return redirect()->route('entrenador.dashboard');
            }
            // Si es dueño, redirigir a su dashboard
            if (auth()->user()->hasRole('dueno')) {
                return redirect()->route('dueno.dashboard');
            }
            
            // Si no tiene ningún rol reconocido
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
} 