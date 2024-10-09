<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }

        // Verificar si el usuario tiene el rol
        if (!$user->hasRole($role)) {
            throw UnauthorizedException::forRoles([$role]);
        }

        return $next($request);
    }
}
