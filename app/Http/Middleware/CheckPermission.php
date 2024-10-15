<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permissions)
    {
        $user = Auth::user();
    
        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }
    
        // Dividimos los permisos por el caracter |
        $permissionsArray = explode('|', $permissions);
    
        // Verificamos si el usuario tiene al menos uno de los permisos
        foreach ($permissionsArray as $permission) {
            if ($user->can($permission)) {
                return $next($request); // Si tiene al menos uno, continúa
            }
        }
    
        // Si no tiene ninguno de los permisos
        Log::info("Permiso faltante: " . implode('|', $permissionsArray));
        throw UnauthorizedException::forPermissions($permissionsArray);
    }
    
}
