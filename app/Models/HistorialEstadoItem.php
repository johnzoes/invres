<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEstadoItem extends Model
{
    use HasFactory;

    protected $table = 'historial_estado_item';

    protected $fillable = [
        'id_detalle_reserva_item',
        'estado',
        'motivo_rechazo',
        'fecha_estado',
    ];

    public $timestamps = false;

    /**
     * Relación con DetalleReservaItem.
     */
    public function detalleReservaItem()
    {
        return $this->belongsTo(DetalleReservaItem::class, 'id_detalle_reserva_item');
    }



}
