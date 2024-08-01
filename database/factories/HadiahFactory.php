<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hadiah>
 */
class HadiahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'point' => $this->faker->numberBetween(100, 1000),
            'image' => $this->faker->imageUrl(),
            'possibility' => $this->faker->randomFloat(2, 0, 1),
        ];
    }
}
