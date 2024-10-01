<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';
    protected $fillable = [
        'codigo_bci', 'descripcion', 'cantidad', 'tipo',
        'marca', 'modelo', 'imagen', 'nro_inventariado',
        'id_categoria', 'id_armario'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function armario()
    {
        return $this->belongsTo(Armario::class, 'id_armario', 'id_armario');
    }

    public function detallesReserva()
    {
        return $this->hasMany(DetalleReservaItem::class, 'id_item', 'id_item');
    }
}
