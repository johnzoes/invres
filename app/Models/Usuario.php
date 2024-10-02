<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles; // Importar el trait

class Usuario extends Model
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
}
