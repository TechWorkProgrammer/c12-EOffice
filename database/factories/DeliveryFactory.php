<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'driver_id' => \App\Models\User::factory(),
            'admin_id' => \App\Models\Admin::factory(),
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'distance' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(['waiting', 'on the way', 'picked up', 'delivered', 'canceled']),
            'weight' => $this->faker->randomFloat(2, 0, 100),
            'description' => $this->faker->paragraph,
            'confirmed_time' => $this->faker->dateTime,
            'estimated_time' => $this->faker->numberBetween(0, 120),
        ];
    }
}
