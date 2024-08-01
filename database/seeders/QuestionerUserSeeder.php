<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionerUser;

class QuestionerUserSeeder extends Seeder
{
    public function run()
    {
        QuestionerUser::factory()->count(10)->create();
    }
}
