<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;  // Asegúrate de importar esta clase

use Closure;

class TestMiddleware
{
    public function handle($request, Closure $next)
    {
        // Agrega un log para verificar si el middleware se está ejecutando
        Log::info('TestMiddleware ejecutado correctamente.');

        // Pasar la solicitud a la siguiente etapa
        return $next($request);
    }
}

