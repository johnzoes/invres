<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    // Definimos la tabla asociada (en plural)
    protected $table = 'roles';  // Cambiado a plural para seguir la convención de Laravel

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre_rol'];  // Cambiado el campo 'nombre' a 'nombre_rol' para seguir la convención de la base de datos

    // Relación: Un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id');
    }

    // Relación: Un rol tiene muchos permisos (relación muchos a muchos)
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'roles_permisos', 'id_rol', 'id_permiso');
    }
}
