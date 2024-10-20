<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos sin duplicar
        $permissions = [
            // Gestión de Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // Gestión de Reservas
            'ver reservas',
            'crear reservas',
            'editar reservas',
            'eliminar reservas',

            // Gestión de Ítems
            'ver items',
            'crear items',
            'editar items',
            'eliminar items',

            // Gestión de Salones y Armarios
            'ver salones',
            'crear salones',
            'editar salones',
            'eliminar salones',
            'ver armarios',
            'crear armarios',
            'editar armarios',
            'eliminar armarios',

            // Gestión de Notificaciones
            'ver notificaciones',
            'marcar notificaciones como leídas',

            // Aprobación de Reservas
            'aprobar reservas',
            'rechazar reservas',
            'prestar ítems',
            'devolver ítems',
        ];

        // Crear los permisos en la base de datos si no existen
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear roles sin duplicar
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $profesor = Role::firstOrCreate(['name' => 'profesor']);
        $asistente = Role::firstOrCreate(['name' => 'asistente']);

        // Asignar permisos al rol de Admin (le damos todos los permisos)
        $admin->syncPermissions(Permission::all());

        // Asignar permisos al rol de Profesor
        $profesorPermissions = [
            'crear reservas',
            'ver reservas',
            'editar reservas',
            'eliminar reservas',
            'ver items',
        ];
        $profesor->syncPermissions($profesorPermissions);

        // Asignar permisos al rol de Asistente
        $asistentePermissions = [
            'ver reservas',
            'aprobar reservas',
            'rechazar reservas',
            'prestar ítems',
            'devolver ítems',
            'ver notificaciones',
            'marcar notificaciones como leídas',
            'ver items',
            'ver salones',
            'ver armarios',
        ];
        $asistente->syncPermissions($asistentePermissions);
    }
}
