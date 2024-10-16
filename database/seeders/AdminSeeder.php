<?php

namespace Database\Seeders;

use App\Models\Usuario; // Asegúrate de usar tu modelo
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear un usuario admin
        $admin = Usuario::create([
            'nombre_usuario' => 'admin',
            'nombre' => 'Administrador',
            'apellidos' => 'Principal',
            'email' => 'admin@example.com', // Agregamos el email
            'password' => Hash::make('adminpassword'), // Cambia la contraseña según prefieras
        ]);

        // Asignar el rol de admin
        $admin->assignRole('admin');
    }
}
