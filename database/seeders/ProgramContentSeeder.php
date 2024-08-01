<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramContent;

class ProgramContentSeeder extends Seeder
{
    public function run()
    {
        ProgramContent::factory()->count(10)->create();
    }
}
