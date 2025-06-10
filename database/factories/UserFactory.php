<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'num_doc' => $this->faker->numberBetween(1000000, 2147483647),
            't_doc' => 1,
            'usu_nombres' => $this->faker->firstName,
            'usu_apellidos' => $this->faker->lastName,
            'usu_fecha_nacimiento' => $this->faker->date(),
            'usu_sexo' => $this->faker->randomElement(['M', 'F']),
            'usu_direccion' => $this->faker->address,
            'usu_telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'usu_fecha_contratacion' => $this->faker->date(),
            'usu_estado' => 1,
            'imag_perfil' => null,
            'external_id' => null,
            'external_auth' => null,
            'password' => 'Password123!',
        ];
    }

}
