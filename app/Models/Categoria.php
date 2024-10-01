<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'categoria';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre_categoria'];
    public $timestamps = false;

    // Relación: Una categoría tiene muchos ítems
    public function items()
    {
        return $this->hasMany(Item::class, 'id_categoria', 'id_categoria');
    }
}
