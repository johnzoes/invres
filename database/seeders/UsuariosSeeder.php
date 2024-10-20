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
        // Crear un usuario profesor si no existe
        $profesor = Usuario::firstOrCreate([
            'email' => 'profesor1@example.com',
        ], [
            'nombre_usuario' => 'profesor1',
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'password' => Hash::make('profesorpassword'),
        ]);

        // Asignar el rol de profesor solo si no lo tiene ya
        if (!$profesor->hasRole('profesor')) {
            $profesor->assignRole('profesor');
        }

        // Relacionar el profesor en la tabla profesores si no existe
        Profesor::firstOrCreate([
            'id_usuario' => $profesor->id,
        ]);

        // Crear un usuario asistente si no existe
        $asistente = Usuario::firstOrCreate([
            'email' => 'asistente1@example.com',
        ], [
            'nombre_usuario' => 'asistente1',
            'nombre' => 'María',
            'apellidos' => 'García',
            'password' => Hash::make('asistentepassword'),
        ]);

        // Asignar el rol de asistente solo si no lo tiene ya
        if (!$asistente->hasRole('asistente')) {
            $asistente->assignRole('asistente');
        }

        // Relacionar el asistente en la tabla asistentes si no existe
        Asistente::firstOrCreate([
            'id_usuario' => $asistente->id,
        ], [
            'id_salon' => 1, // Ajusta este valor si es necesario
            'turno' => 'mañana',
        ]);
    }
}
