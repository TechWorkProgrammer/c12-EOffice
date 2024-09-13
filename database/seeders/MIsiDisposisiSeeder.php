<?php

namespace Database\Seeders;

use App\Models\MIsiDisposisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MIsiDisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MIsiDisposisi::factory(30)->create();
    }
}
