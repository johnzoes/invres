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
        $salon1 = Salon::firstOrCreate(['nombre_salon' => 'Laboratorio de Tecnología']);
        $salon2 = Salon::firstOrCreate(['nombre_salon' => 'Taller de Electrónica']);

        // Crear categorías representativas
        $categoria1 = Categoria::firstOrCreate(['nombre_categoria' => 'Laptops']);
        $categoria2 = Categoria::firstOrCreate(['nombre_categoria' => 'Cables UTP']);
        $categoria3 = Categoria::firstOrCreate(['nombre_categoria' => 'Multímetros']);
        $categoria4 = Categoria::firstOrCreate(['nombre_categoria' => 'Proyectores']);

        // Crear armarios relacionados con los salones
        $armario1 = Armario::firstOrCreate(['nombre_armario' => 'Armario de Tecnología', 'id_salon' => $salon1->id]);
        $armario2 = Armario::firstOrCreate(['nombre_armario' => 'Armario de Electrónica', 'id_salon' => $salon2->id]);

        // Crear ítems específicos para cada categoría
        // Laptops (unidades únicas con códigos BCI)
        Item::create([
            'descripcion' => 'Laptop HP Core i5',
            'codigo_bci' => 'BCI-LPT-001',
            'id_categoria' => $categoria1->id,
            'id_armario' => $armario1->id,
            'cantidad' => 1, // Unidades individuales
            'estado' => 'disponible',
        ]);

        Item::create([
            'descripcion' => 'Laptop Dell Core i7',
            'codigo_bci' => 'BCI-LPT-002',
            'id_categoria' => $categoria1->id,
            'id_armario' => $armario1->id,
            'cantidad' => 1, // Unidades individuales
            'estado' => 'disponible',
        ]);

        // Cables UTP (por paquetes)
        Item::create([
            'descripcion' => 'Cable UTP Cat6',
            'codigo_bci' => null, // Paquetes no tienen código único
            'id_categoria' => $categoria2->id,
            'id_armario' => $armario1->id,
            'cantidad' => 50, // Cantidad total disponible en inventario
            'estado' => 'disponible',
        ]);

        // Multímetros (unidades únicas)
        Item::create([
            'descripcion' => 'Multímetro Digital Fluke',
            'codigo_bci' => 'BCI-MTR-001',
            'id_categoria' => $categoria3->id,
            'id_armario' => $armario2->id,
            'cantidad' => 1, // Unidades individuales
            'estado' => 'disponible',
        ]);

        Item::create([
            'descripcion' => 'Multímetro Analógico',
            'codigo_bci' => 'BCI-MTR-002',
            'id_categoria' => $categoria3->id,
            'id_armario' => $armario2->id,
            'cantidad' => 1, // Unidades individuales
            'estado' => 'disponible',
        ]);

        // Proyectores (unidades únicas)
        Item::create([
            'descripcion' => 'Proyector Epson XGA',
            'codigo_bci' => 'BCI-PRJ-001',
            'id_categoria' => $categoria4->id,
            'id_armario' => $armario1->id,
            'cantidad' => 1, // Unidades individuales
            'estado' => 'disponible',
        ]);

        Item::create([
            'descripcion' => 'Proyector BenQ Full HD',
            'codigo_bci' => 'BCI-PRJ-002',
            'id_categoria' => $categoria4->id,
            'id_armario' => $armario1->id,
            'cantidad' => 1, // Unidades individuales
            'estado' => 'disponible',
        ]);
    }
}
