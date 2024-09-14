<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KotamaSeeder::class,
            SatminkalSeeder::class,
            MPejabatSeeder::class,
            MUserSeeder::class,
            MKlasifikasiSuratSeeder::class,
            MIsiDisposisiSeeder::class,
        ]);
    }
}
