<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadoReserva extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'estado_reserva';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['id_reserva', 'id_asistente', 'estado', 'motivo_rechazo', 'hora_estado', 'fecha_estado'];

    // Relación: Un estado de reserva pertenece a una reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id_reserva');
    }

    // Relación: Un estado de reserva es gestionado por un asistente
    public function asistente()
    {
        return $this->belongsTo(Asistente::class, 'id_asistente', 'id_asistente');
    }
}
