<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistente extends Model
{
    use HasFactory;

    protected $table = 'asistentes';  // Nombre de la tabla en plural

    protected $fillable = ['id_usuario', 'turno'];

    public $timestamps = false;

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }

    // Nueva relación muchos a muchos con el modelo Salon
    public function salones()
    {
        return $this->belongsToMany(Salon::class, 'asistente_salon', 'asistente_id', 'salon_id');
    }

    // Relación con las notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_asistente', 'id');
    }
}
