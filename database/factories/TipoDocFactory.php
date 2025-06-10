<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TipoDoc;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoDoc>
 */
class TipoDocFactory extends Factory
{
    protected $model = TipoDoc::class;

    public function definition(): array
    {
        return [
            'tip_doc_descripcion' => $this->faker->word(),
        ];
    }
}
