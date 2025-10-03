<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Sucursale;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::all()->count();
        $sucursal = Sucursale::all()->count();
        $user = User::all()->count();
        return [
            // Producto
            'name' => fake()->name(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 1000),
            'stock' => fake()->numberBetween(0, 100),
            'active' => true,
            'category_id' => fake()->numberBetween(1, $category),
            'sucursale_id' => fake()->numberBetween(1, $sucursal),
            'user_id' => fake()->numberBetween(1, $user),
        ];
    }
}
