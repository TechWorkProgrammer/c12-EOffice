<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoryPoint>
 */
class HistoryPointFactory extends Factory
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
            'trash_pickup_id' => \App\Models\TrashPickup::factory(),
            'hadiah_id' => \App\Models\Hadiah::factory(),
            'description' => $this->faker->paragraph,
            'point' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
