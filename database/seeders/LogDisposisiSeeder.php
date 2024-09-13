<?php

namespace Database\Seeders;

use App\Models\LogDisposisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogDisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LogDisposisi::factory(50)->create();
    }
}
