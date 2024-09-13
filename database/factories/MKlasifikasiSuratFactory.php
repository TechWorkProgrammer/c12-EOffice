<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MKlasifikasiSuratFactory extends Factory
{
    protected $model = \App\Models\MKlasifikasiSurat::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'name' => $this->faker->unique()->randomElement(['Rahasia', 'Biasa', 'Segera']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
