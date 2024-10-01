<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnidadDidactica extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'unidad_didactica';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['nombre', 'ciclo'];

    // Relación: Una unidad didáctica tiene muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_unidad_didactica', 'id_unidad_didactica');
    }
}
