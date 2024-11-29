<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salon extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'salones';
    public $timestamps = true;

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre_salon'];

    // Relación: Un salón tiene muchos armarios
    public function armarios()
    {
        return $this->hasMany(Armario::class, 'id_salon', 'id');
    }

    // Nueva Relación: Un salón tiene muchos asistentes (muchos a muchos)
    public function asistentes()
    {
        return $this->belongsToMany(Asistente::class, 'asistente_salon', 'salon_id', 'asistente_id');
    }
}
