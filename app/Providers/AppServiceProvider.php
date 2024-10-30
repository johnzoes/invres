<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;

use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            // Verificar si el usuario está autenticado
            if (auth()->check()) {
                // Obtener el ID del usuario
                $asistente = auth()->user()->asistente;

                // Obtener las notificaciones
                $notificaciones = Notificacion::where('id_asistente', $asistente->id)
                                              ->where('es_leida', 0)
                                              ->get();
            } else {
                $notificaciones = collect(); // Colección vacía si no hay usuario autenticado
            }
    
            // Compartir las notificaciones con todas las vistas
            $view->with('notificaciones', $notificaciones);
        });
    }
    
}
