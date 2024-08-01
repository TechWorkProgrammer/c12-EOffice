<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Questioner;

class QuestionerSeeder extends Seeder
{
    public function run()
    {
        Questioner::factory()->count(5)->create();
    }
}
