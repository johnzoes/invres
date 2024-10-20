<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear un usuario admin si no existe
        $admin = Usuario::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'nombre_usuario' => 'admin',
            'nombre' => 'Administrador',
            'apellidos' => 'Principal',
            'password' => Hash::make('adminpassword'),
        ]);

        // Asignar el rol de admin solo si no lo tiene ya
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
