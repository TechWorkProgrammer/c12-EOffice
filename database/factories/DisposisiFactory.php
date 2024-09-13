<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DisposisiFactory extends Factory
{
    protected $model = \App\Models\Disposisi::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'surat_id' => \App\Models\SuratMasuk::inRandomOrder()->value('uuid'),
            'disposisi_asal' => $this->faker->randomElement([\App\Models\Disposisi::inRandomOrder()->value('uuid'), null]),
            'catatan' => $this->faker->text,
            'tanda_tangan' => 'path/to/signature.png',
            'created_by' => \App\Models\MUser::inRandomOrder()->value('uuid'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
