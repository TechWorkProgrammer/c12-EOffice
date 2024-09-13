<?php

namespace Database\Seeders;

use App\Models\Disposisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Disposisi::factory(16)->create();
    }
}
