<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserva extends Model
{
    use HasFactory;

    // Definimos la tabla asociada
    protected $table = 'reservas';

    // Definimos los campos que se pueden asignar de forma masiva
    protected $fillable = ['fecha_prestamo', 'fecha_devolucion', 'id_profesor', 'id_unidad_didactica', 'turno'];

    // Relación: Una reserva pertenece a un profesor
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'id_profesor', 'id');
    }

    // Relación: Una reserva pertenece a una unidad didáctica
    public function unidadDidactica()
    {
        return $this->belongsTo(UnidadDidactica::class, 'id_unidad_didactica', 'id');
    }

    // Relación: Una reserva tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleReservaItem::class, 'id_reserva', 'id');
    }

    // Relación: Una reserva tiene muchos estados
    public function estados()
    {
        return $this->hasMany(EstadoReserva::class, 'id_reserva', 'id');
    }
}
