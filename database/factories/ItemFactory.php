<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'codigo_bci' => $this->faker->unique()->numerify('BCI###'),
            'descripcion' => $this->faker->sentence,
            'cantidad' => $this->faker->numberBetween(1, 100),
            'tipo' => $this->faker->randomElement(['unidad', 'paquete']),
            'marca' => $this->faker->company,
            'modelo' => $this->faker->word,
            'nro_inventariado' => $this->faker->numerify('INV###'),
            'id_categoria' => null, // Se definirá en el seeder
            'id_armario' => null,   // Se definirá en el seeder
        ];
    }
}
