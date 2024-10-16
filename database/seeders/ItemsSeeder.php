<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Categoria;
use App\Models\Armario;
use App\Models\Salon;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear salones de ejemplo si no existen
        $salon1 = Salon::firstOrCreate(['nombre_salon' => 'Salon A']);
        $salon2 = Salon::firstOrCreate(['nombre_salon' => 'Salon B']);

        // Crear categorías de ejemplo si no existen
        $categoria1 = Categoria::firstOrCreate(['nombre_categoria' => 'Tecnología']);
        $categoria2 = Categoria::firstOrCreate(['nombre_categoria' => 'Electrónica']);
        $categoria3 = Categoria::firstOrCreate(['nombre_categoria' => 'Oficina']);

        // Crear armarios con un id_salon válido
        $armario1 = Armario::firstOrCreate(['nombre_armario' => 'Armario 1', 'id_salon' => $salon1->id]);
        $armario2 = Armario::firstOrCreate(['nombre_armario' => 'Armario 2', 'id_salon' => $salon2->id]);

        // Ahora creamos 10 items de prueba
        Item::factory()->count(10)->create([
            'id_categoria' => $categoria1->id,
            'id_armario' => $armario1->id,
        ]);
    }
}
