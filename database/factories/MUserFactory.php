<?php

namespace Database\Factories;

use App\Models\MPejabat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MUser>
 */
class MUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\MUser::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'role' => $this->faker->randomElement(['Tata Usaha', 'Pejabat', 'Pelaksana']),
            'pejabat_id' => null,
            'password' => bcrypt('password123'), // Default password
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function pejabat(MPejabat $pejabat)
    {
        return $this->state(function () use ($pejabat) {
            return [
                'role' => 'Pejabat',
                'pejabat_id' => $pejabat->uuid,
            ];
        });
    }
}
