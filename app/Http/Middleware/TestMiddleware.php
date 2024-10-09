<?php

namespace App\Http\Middleware;

use Closure;

class TestMiddleware
{
    public function handle($request, Closure $next)
    {
        // Puedes agregar este log para ver si el middleware se ejecuta
        \Log::info('TestMiddleware ejecutado correctamente.');

        // Deja que la solicitud continúe
        return $next($request);
    }
}

