<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'usuario';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'nombre_usuario', 'nombre', 'apellidos', 'password', 'id_rol'
    ];

    // Relación: Un usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id');
    }

    // Relación: Un usuario puede ser un profesor
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id_usuario', 'id');
    }

    // Relación: Un usuario puede ser un asistente
    public function asistente()
    {
        return $this->hasOne(Asistente::class, 'id_usuario', 'id');
    }
}
