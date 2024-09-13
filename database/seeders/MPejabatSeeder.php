<?php

namespace Database\Seeders;

use App\Models\MPejabat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MPejabatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Level Pejabat Tingkat Atasan (Level 1)
        $komandanA = MPejabat::factory()->create(['name' => 'Komandan A', 'atasan_id' => null]);
        $komandanB = MPejabat::factory()->create(['name' => 'Komandan B', 'atasan_id' => null]);
        $komandanC = MPejabat::factory()->create(['name' => 'Komandan C', 'atasan_id' => null]);

        // Level Pejabat Bawahan Langsung (Level 2)
        $kasubA = MPejabat::factory()->create(['name' => 'Kasub A', 'atasan_id' => $komandanA->uuid]);
        $kasubB = MPejabat::factory()->create(['name' => 'Kasub B', 'atasan_id' => $komandanB->uuid]);
        $kasubC = MPejabat::factory()->create(['name' => 'Kasub C', 'atasan_id' => $komandanC->uuid]);

        // Level Pejabat Bawahannya Bawahan (Level 3)
        MPejabat::factory()->create(['name' => 'Kabag A', 'atasan_id' => $kasubA->uuid]);
        MPejabat::factory()->create(['name' => 'Kabag B', 'atasan_id' => $kasubB->uuid]);
        MPejabat::factory()->create(['name' => 'Kabag C', 'atasan_id' => $kasubC->uuid]);
    }
}
