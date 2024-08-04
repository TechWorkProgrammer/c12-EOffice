<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionerUser>
 */
class QuestionerUserFactory extends Factory
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
            'questioner_id' => \App\Models\Questioner::factory(),
            'answer' => $this->faker->numberBetween(1, 5), // Menghasilkan nilai integer antara 1 dan 5
        ];
    }
}
