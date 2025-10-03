<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sucursale>
 */
class SucursaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->count();
        return [
            // Sucursal
            'nombre' => fake()->company(),
            'direccion' => fake()->address(),
            'activo' => true,
            'principal' => false,
            'user_id' => fake()->numberBetween(1, $user),
        ];
    }
}
