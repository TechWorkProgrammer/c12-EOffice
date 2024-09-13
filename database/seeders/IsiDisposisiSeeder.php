<?php

namespace Database\Seeders;

use App\Models\IsiDisposisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IsiDisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IsiDisposisi::factory(40)->create();
    }
}
