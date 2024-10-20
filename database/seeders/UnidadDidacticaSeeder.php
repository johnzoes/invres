<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadDidactica;

class UnidadDidacticaSeeder extends Seeder
{
    public function run()
    {
        // Insertar unidades didácticas sin duplicar
        $unidades = [
            ['nombre' => 'Matemáticas', 'ciclo' => 'Ciclo 1'],
            ['nombre' => 'Física', 'ciclo' => 'Ciclo 2'],
            ['nombre' => 'Química', 'ciclo' => 'Ciclo 3'],
            ['nombre' => 'Biología', 'ciclo' => 'Ciclo 4'],
            ['nombre' => 'Informática', 'ciclo' => 'Ciclo 5'],
        ];

        foreach ($unidades as $unidad) {
            UnidadDidactica::firstOrCreate([
                'nombre' => $unidad['nombre'],
                'ciclo' => $unidad['ciclo'],
            ]);
        }
    }
}
