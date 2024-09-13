<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MIsiDisposisiFactory extends Factory
{
    protected $model = \App\Models\MIsiDisposisi::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'isi' => $this->faker->unique->randomElement([
                'Ikuti perkembangannya',
                'Chek / periksa',
                'Sosialisasikan',
                'Sebagai bahan',
                'Tindaklanjuti',
                'Koordinasikan',
                'Laporkan hasilnya',
                'Selesaikan',
                'Pedomani',
                'Buat Sprin',
                'Tepati waktu',
                'Tidak hadir',
                'Siapkan',
                'Monitor',
                'Ingatkan',
                'Dukung',
                'Himpun',
                'Acarakan',
                'Bantu',
                'Balas / Jawab',
                'Catat',
                'Saya Hadir',
                'Wakili Saya',
                'Pelajari',
                'Saran',
                'ACC',
                'UMP',
                'UDK',
                'UDL',
                'Arsipkan'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
