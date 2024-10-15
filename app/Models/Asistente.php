<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistente extends Model
{
    use HasFactory;

    protected $table = 'asistentes';  // Nombre de la tabla en plural

    protected $fillable = ['id_usuario', 'id_salon', 'turno'];

  public $timestamps = false;


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'id_salon', 'id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_asistente', 'id');
    }
}
