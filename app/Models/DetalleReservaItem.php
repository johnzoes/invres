<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleReservaItem extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'detalle_reserva_item';
    public $timestamps = false;

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'id_reserva', 'id_item', 'fecha_reserva', 
        'hora_reserva', 'cantidad_reservada', 'estado'
    ];

    // Relación: Un detalle de reserva pertenece a una reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id');
    }

    // Relación: Un detalle de reserva pertenece a un ítem
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id');
    }

    // Relación: Un detalle de reserva tiene múltiples registros en el historial
    public function historialEstados()
    {
        return $this->hasMany(HistorialEstadoItem::class, 'id_detalle_reserva_item', 'id');
    }

    // Método para obtener el último estado del historial
    public function ultimoEstado()
    {
        return $this->historialEstados()->latest('fecha_estado')->first();
    }

    // Helper method para verificar el estado actual
    public function tieneEstado($estado)
    {
        return $this->estado === $estado;
    }

}
