<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RolesSeeder;

use Database\Seeders\ItemsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\UnidadDidacticaSeeder;
use Database\Seeders\UsuariosSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
            ItemsSeeder::class,
            UnidadDidacticaSeeder::class,
        ]);
    }
}
