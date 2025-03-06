<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cliente;
use App\Observers\ClienteObserver;
use Illuminate\Support\Facades\View;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cliente::observe(ClienteObserver::class);

        View::composer(['layouts.navigation', 'layouts.cliente'], function ($view) {
            if (auth()->check() && auth()->user()->hasRole('cliente')) {
                $notificacionesNoLeidas = Notificacion::where('user_id', auth()->id())
                    ->where('leida', false)
                    ->count();
                
                $view->with('notificacionesNoLeidas', $notificacionesNoLeidas);
            }
        });

        // Registrar el componente ClienteLayout
        Blade::component('cliente-layout', \App\View\Components\ClienteLayout::class);
    }
}
