<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HistoryPoint;

class HistoryPointSeeder extends Seeder
{
    public function run()
    {
        HistoryPoint::factory()->count(10)->create();
    }
}
