<?php

namespace Database\Factories;

use App\Models\MPejabat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MPejabatFactory extends Factory
{
    protected $model = \App\Models\MPejabat::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'atasan_id' => null, // default value untuk atasan_id, nanti di-seeder akan di-update
            // tambahkan field lain yang diperlukan
        ];
    }
}
