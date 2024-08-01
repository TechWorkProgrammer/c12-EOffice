<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => '08' . $this->faker->unique()->numberBetween(10000000, 99999999),
            'password' => bcrypt('password'),
            'address' => $this->faker->address,
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'birthdate' => $this->faker->date(),
            'role' => $this->faker->randomElement(['user', 'driver']),
            'is_verified' => $this->faker->boolean,
            'point' => $this->faker->numberBetween(0, 1000),
            'questioner_submitted' => $this->faker->boolean,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
