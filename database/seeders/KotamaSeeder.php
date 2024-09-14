<?php

namespace Database\Seeders;

use App\Models\Kotama;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KotamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kotama::create([
            'kode' => '01',
            'name' => 'KODAM I/BB'
        ]);
    }
}
