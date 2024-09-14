<?php

namespace Database\Seeders;

use App\Models\Satminkal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatminkalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Satminkal::create([
            'kode_kotama' => '01',
            'kode_satminkal' => '1A0B',
            'name' => 'YONIF RAIDER 100/PS',
        ]);
    }
}
