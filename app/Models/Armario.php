<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Armario extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'armario';

    // Definimos la clave primaria correcta
    protected $primaryKey = 'id_armario';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre_armario', 'id_salon'];

    // Relación: Un armario pertenece a un salón
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id_salon');
    }

    // Relación: Un armario tiene muchos ítems
    public function items()
    {
        return $this->hasMany(Item::class, 'id_armario', 'id_armario');
    }
}
