<?php

namespace Database\Seeders;

use App\Models\MKlasifikasiSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MKlasifikasiSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MKlasifikasiSurat::factory(3)->create();
    }
}
