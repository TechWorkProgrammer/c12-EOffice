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
        $pejabatA = MPejabat::factory()->create(['name' => 'Pejabat A', 'atasan_id' => null]);
        $pejabatB = MPejabat::factory()->create(['name' => 'Pejabat B', 'atasan_id' => null]);
        $pejabatC = MPejabat::factory()->create(['name' => 'Pejabat C', 'atasan_id' => null]);

        // Level Pejabat Bawahan Langsung (Level 2)
        $pejabatAA = MPejabat::factory()->create(['name' => 'Pejabat AA', 'atasan_id' => $pejabatA->uuid]);
        $pejabatBA = MPejabat::factory()->create(['name' => 'Pejabat BA', 'atasan_id' => $pejabatB->uuid]);
        $pejabatCA = MPejabat::factory()->create(['name' => 'Pejabat CA', 'atasan_id' => $pejabatC->uuid]);
        $pejabatCB = MPejabat::factory()->create(['name' => 'Pejabat CB', 'atasan_id' => $pejabatC->uuid]);

        // Level Pejabat Bawahannya Bawahan (Level 3)
        MPejabat::factory()->create(['name' => 'Pejabat AAA', 'atasan_id' => $pejabatAA->uuid]);
        MPejabat::factory()->create(['name' => 'Pejabat BAA', 'atasan_id' => $pejabatBA->uuid]);
        MPejabat::factory()->create(['name' => 'Pejabat BAB', 'atasan_id' => $pejabatBA->uuid]);
    }
}
