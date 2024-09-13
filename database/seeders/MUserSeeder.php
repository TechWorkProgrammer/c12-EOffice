<?php

namespace Database\Seeders;

use App\Models\MPejabat;
use App\Models\MUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua pejabat
        $pejabats = MPejabat::all();

        // Pastikan jumlah pejabat sama dengan jumlah pengguna dengan role Pejabat
        foreach ($pejabats as $pejabat) {
            MUser::factory()->pejabat($pejabat)->create();
        }

        // Buat beberapa pengguna tambahan dengan role selain Pejabat
        MUser::factory()->count(20)->create([
            'role' => fake()->randomElement(['Tata Usaha', 'Pelaksana']),
            'pejabat_id' => null,
        ]);
    }
}
