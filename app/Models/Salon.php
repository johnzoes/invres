<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salon extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'salon';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre_salon'];



    // Relación: Un salón tiene muchos armarios
    public function armarios()
    {
        return $this->hasMany(Armario::class, 'id_salon', 'id_salon');
    }

    // Relación: Un salón tiene muchos asistentes
    public function asistentes()
    {
        return $this->hasMany(Asistente::class, 'id_salon', 'id_salon');
    }
}
