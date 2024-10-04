<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable; 

use Spatie\Permission\Traits\HasRoles; // Importar el trait

class Usuario extends Authenticatable 
{
    use HasRoles; // Usar el trait para roles y permisos

    protected $table = 'usuarios';
    protected $fillable = ['nombre_usuario', 'nombre', 'apellidos', 'password'];

    // Relación con Profesor
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id_usuario', 'id');
    }

    // Relación con Asistente
    public function asistente()
    {
        return $this->hasOne(Asistente::class, 'id_usuario', 'id');
    }

        // Campos que deben estar ocultos para arrays (como el hash de contraseñas)
        protected $hidden = [
            'password', 'remember_token',
        ];
}
