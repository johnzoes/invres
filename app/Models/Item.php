<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'items';

    // Campos que se pueden llenar de forma masiva
    protected $fillable = [
        'codigo_bci', 'descripcion', 'cantidad', 'tipo', 'estado',
        'marca', 'modelo', 'imagen', 'nro_inventariado',
        'id_categoria', 'id_armario'
    ];

    // Relación con la tabla 'categorias'
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id');
    }

    // Relación con la tabla 'armarios'
    public function armario()
    {
        return $this->belongsTo(Armario::class, 'id_armario', 'id');
    }

    // Relación con la tabla 'detalle_reserva_items'
    public function detallesReserva()
    {
        return $this->hasMany(DetalleReservaItem::class, 'id_item', 'id');
    }

    // Método para actualizar el estado basado en la cantidad disponible
    public function actualizarEstado()
    {
        if ($this->tipo === 'unidad') {
            // Si es una unidad, cambiar a 'ocupado' si la cantidad es 0
            $this->estado = $this->cantidad > 0 ? 'disponible' : 'ocupado';
        } elseif ($this->tipo === 'paquete') {
            // Si es un paquete, mostrar la cantidad disponible
            $this->estado = $this->cantidad > 0 ? 'disponible' : 'ocupado';
        }
        $this->save();
    }
}
