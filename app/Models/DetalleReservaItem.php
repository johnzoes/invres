<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleReservaItem extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'detalle_reserva_item';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'id_reserva', 'id_item', 'fecha_reserva', 
        'hora_reserva', 'cantidad_reservada', 'estado'
    ];

    // Relación: Un detalle de reserva pertenece a una reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id_reserva');
    }

    // Relación: Un detalle de reserva pertenece a un ítem
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
