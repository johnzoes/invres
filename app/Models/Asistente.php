<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistente extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'asistente';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['id_usuario', 'id_salon', 'turno'];

    // Relación: Un asistente pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Relación: Un asistente pertenece a un salón
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    // Relación: Un asistente tiene muchas notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_asistente', 'id_asistente');
    }
}
