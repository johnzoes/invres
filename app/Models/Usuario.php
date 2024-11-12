<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Importar el trait

class Usuario extends Authenticatable 
{

    use Notifiable, HasRoles;
    protected $table = 'usuarios';
    
    protected $fillable = [
        'nombre_usuario',
        'nombre',
        'apellidos',
        'password',
        'email',  // Asegúrate de que este campo esté incluido
    ];
    
    // Relación con Profesor
    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id_usuario', 'id');
    }

    // Relación con Asistente
    public function asistente()
    {
        return $this->hasOne(Asistente::class, 'id_usuario', 'id');
    }

    // Relación con Reserva (Un Usuario puede tener muchas Reservas)
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_usuario', 'id'); // 'id_usuario' es la clave foránea en la tabla reservas
    }

    // Campos que deben estar ocultos para arrays (como el hash de contraseñas)
    protected $hidden = [
        'password', 'remember_token',
    ];
}
