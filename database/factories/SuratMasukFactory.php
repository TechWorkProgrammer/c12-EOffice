<?php

namespace Database\Factories;

use App\Models\MKlasifikasiSurat;
use App\Models\MUser;
use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuratMasukFactory extends Factory
{
    protected $model = SuratMasuk::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'klasifikasi_surat_id' => MKlasifikasiSurat::inRandomOrder()->value('uuid'),
            'nomor_surat' => $this->faker->numerify('SM-###'),
            'tanggal_surat' => $this->faker->date(),
            'pengirim' => $this->faker->company,
            'perihal' => $this->faker->sentence,
            'file_surat' => 'path/to/surat.pdf',
            'created_by' => MUser::inRandomOrder()->value('uuid'),
            'penerima_id' => MUser::inRandomOrder()->value('uuid'),
            'read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
