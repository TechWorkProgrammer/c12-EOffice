<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IsiDisposisiFactory extends Factory
{
    protected $model = \App\Models\IsiDisposisi::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'disposisi_id' => \App\Models\Disposisi::inRandomOrder()->value('uuid'),
            'isi_disposisi_id' => \App\Models\MIsiDisposisi::inRandomOrder()->value('uuid'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
