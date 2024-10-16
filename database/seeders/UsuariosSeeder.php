<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Profesor;
use App\Models\Asistente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        // Crear un usuario profesor
        $profesor = Usuario::create([
            'nombre_usuario' => 'profesor1',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'profesor1@example.com',
            'password' => Hash::make('profesorpassword'),
        ]);

        // Asignar el rol de profesor
        $profesor->assignRole('profesor');

        // Relacionar el profesor en la tabla profesores
        Profesor::create([
            'id_usuario' => $profesor->id,
        ]);

        // Crear un usuario asistente
        $asistente = Usuario::create([
            'nombre_usuario' => 'asistente1',
            'nombre' => 'María',
            'apellidos' => 'García',
            'email' => 'asistente1@example.com',
            'password' => Hash::make('asistentepassword'),
        ]);

        // Asignar el rol de asistente
        $asistente->assignRole('asistente');

        // Relacionar el asistente en la tabla asistentes
        Asistente::create([
            'id_usuario' => $asistente->id,
            'id_salon' => 1, // Debes asegurarte de que el salón existe, o ajustarlo según tu base de datos.
            'turno' => 'mañana', // Puedes ajustar el valor según los valores válidos en tu base de datos
        ]);
    }
}
