<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PojokEdukasiContent;

class PojokEdukasiContentSeeder extends Seeder
{
    public function run()
    {
        PojokEdukasiContent::factory()->count(10)->create();
    }
}
