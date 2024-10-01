<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacion extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'notificacion';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['id_asistente', 'id_reserva', 'mensaje', 'es_leida'];

    // Relación: Una notificación pertenece a un asistente
    public function asistente()
    {
        return $this->belongsTo(Asistente::class, 'id_asistente', 'id_asistente');
    }

    // Relación: Una notificación pertenece a una reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id_reserva');
    }
}
