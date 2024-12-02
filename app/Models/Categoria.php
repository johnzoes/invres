<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';  // Nombre de la tabla en plural

    protected $fillable = ['nombre_categoria','imagen'
];

    public function items()
    {
        return $this->hasMany(Item::class, 'id_categoria', 'id');
    }
}
