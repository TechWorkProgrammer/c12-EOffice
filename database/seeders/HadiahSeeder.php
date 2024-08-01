<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hadiah;

class HadiahSeeder extends Seeder
{
    public function run()
    {
        Hadiah::factory()->count(5)->create();
    }
}
