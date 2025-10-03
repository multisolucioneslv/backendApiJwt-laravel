<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Phone;
use App\Models\Telegram;
use App\Models\User;
use App\Models\Sex;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phone = Phone::all()->count();
        $telegram = Telegram::all()->count();
        $user = User::all()->count();
        $sex = Sex::all()->count();
        return [
            // Cliente
            'name' => fake()->name(),
            'lastname' => fake()->lastName(),
            'email' => fake()->email(),
            'phone_id' => fake()->numberBetween(1, $phone),
            'telegram_id' => fake()->numberBetween(1, $telegram),
            'user_id' => fake()->numberBetween(1, $user),
            'sex_id' => fake()->numberBetween(1, $sex),
            'address' => fake()->address(),
        ];
    }
}
