<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';  // Nombre de la tabla en plural

    protected $fillable = ['id_asistente', 'id_reserva', 'mensaje', 'es_leida'];

    public function asistente()
    {
        return $this->belongsTo(Asistente::class, 'id_asistente', 'id');
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id');
    }
}
