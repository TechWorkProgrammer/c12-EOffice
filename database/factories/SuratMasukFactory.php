<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuratMasukFactory extends Factory
{
    protected $model = \App\Models\SuratMasuk::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'klasifikasi_surat_id' => \App\Models\MKlasifikasiSurat::inRandomOrder()->value('uuid'),
            'nomor_surat' => $this->faker->numerify('SM-###'),
            'tanggal_surat' => $this->faker->date(),
            'pengirim' => $this->faker->company,
            'perihal' => $this->faker->sentence,
            'file_surat' => 'path/to/surat.pdf',
            'created_by' => \App\Models\MUser::inRandomOrder()->value('uuid'),
            'penerima_id' => \App\Models\MUser::inRandomOrder()->value('uuid'),
            'read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
