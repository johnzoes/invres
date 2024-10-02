<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnidadDidactica extends Model
{
    use HasFactory;

    protected $table = 'unidades_didacticas';  // Nombre de la tabla en plural

    protected $fillable = ['nombre', 'ciclo'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_unidad_didactica', 'id');
    }
}
