<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos
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

        // Crear los permisos en la base de datos
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $profesor = Role::create(['name' => 'profesor']);
        $asistente = Role::create(['name' => 'asistente']);

        // Asignar permisos al rol de Admin
        $admin->givePermissionTo(Permission::all());

        // Asignar permisos al rol de Profesor
        $profesor->givePermissionTo([
            'crear reservas',
            'ver reservas',
            'editar reservas',
            'eliminar reservas',
            'ver items',
        ]);

        // Asignar permisos al rol de Asistente
        $asistente->givePermissionTo([
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
        ]);
    }
}
