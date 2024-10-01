<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'rol';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre'];

    // Relación: Un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }

    // Relación: Un rol tiene muchos permisos
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'role_permission', 'id_rol', 'id_permiso');
    }
}
