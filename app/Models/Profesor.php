<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profesor extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'profesores';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['id_usuario'];

    public $timestamps = false;

    // Relación: Un profesor pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }

    // Relación: Un profesor tiene muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_profesor', 'id');
    }
}
