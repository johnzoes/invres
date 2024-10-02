<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permiso extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'permisos';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre'];

    // Relación: Un permiso puede pertenecer a muchos roles
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'role_permission', 'id_permiso', 'id_rol');
    }
}
