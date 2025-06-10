<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MateriaPrima>
 */
class MateriaPrimaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mat_pri_nombre' => $this->faker->word(),
            'mat_pri_descripcion' => $this->faker->text(40),
            'mat_pri_unidad_medida' => 'metros',
            'mat_pri_cantidad' => $this->faker->numberBetween(10, 500),
            'mat_pri_estado' => 1, // puedes ajustar según tus estados reales
            'fecha_compra_mp' => $this->faker->date(),
            'proveedores_id_proveedores' => 4869681

        ];
    }
}
