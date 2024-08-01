<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PojokEdukasiContent>
 */
class PojokEdukasiContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pojok_edukasi_id' => \App\Models\PojokEdukasi::factory(),
            'name' => $this->faker->word,
            'link' => $this->faker->url,
        ];
    }
}
