<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Phone;
use App\Models\Telegram;
use App\Models\Sex;

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
        $phone = Phone::all()->count();
        $telegram = Telegram::all()->count();
        $sex = Sex::all()->count();
        return [
            'username' => fake()->unique()->username(),
            'name' => fake()->name(),
            'lastname' => fake()->lastName(),
            'phone_id' => fake()->numberBetween(1, $phone),
            'telegram_id' => fake()->numberBetween(1, $telegram),
            'sex_id' => fake()->numberBetween(1, $sex),
            'status' => 'activo',
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
