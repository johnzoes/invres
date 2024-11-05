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
            // Asignar una colección vacía por defecto
            $notificaciones = collect();

            if (auth()->check()) {
                $user = auth()->user();

                // Verificar si el usuario tiene un asistente asociado
                if ($user->asistente) {
                    $asistente = $user->asistente;

                    // Obtener las notificaciones no leídas del asistente
                    $notificaciones = Notificacion::where('id_asistente', $asistente->id)
                                                  ->where('es_leida', 0)
                                                  ->get();
                }
            }

            // Compartir las notificaciones con todas las vistas
            $view->with('notificaciones', $notificaciones);
        });
    }
    
}
