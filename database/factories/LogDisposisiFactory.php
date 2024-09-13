<?php

namespace Database\Factories;

use App\Models\Disposisi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LogDisposisiFactory extends Factory
{
    protected $model = \App\Models\LogDisposisi::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'pengirim' => \App\Models\MUser::inRandomOrder()->value('uuid'),
            'penerima' => \App\Models\MUser::inRandomOrder()->value('uuid'),
            'disposisi_id' => Disposisi::inRandomOrder()->value('uuid'),
            'read_at' => now(),
            'pelaksanaan_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
