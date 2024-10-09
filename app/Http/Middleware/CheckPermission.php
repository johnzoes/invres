<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }

        // Verificar si el usuario tiene el permiso
        if (!$user->can($permission)) {
            throw UnauthorizedException::forPermissions([$permission]);
        }

        return $next($request);
    }
}
