<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear el rol de admin si no existe
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        // Crear el rol de profesor si no existe
        if (!Role::where('name', 'profesor')->exists()) {
            Role::create(['name' => 'profesor']);
        }

        // Crear el rol de asistente si no existe
        if (!Role::where('name', 'asistente')->exists()) {
            Role::create(['name' => 'asistente']);
        }
    }
}
