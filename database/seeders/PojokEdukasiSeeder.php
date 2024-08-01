<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PojokEdukasi;

class PojokEdukasiSeeder extends Seeder
{
    public function run()
    {
        PojokEdukasi::factory()->count(5)->create();
    }
}
