<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MPejabatSeeder::class,
            MUserSeeder::class,
            MKlasifikasiSuratSeeder::class,
            SuratMasukSeeder::class,
            MIsiDisposisiSeeder::class,
            DisposisiSeeder::class,
            IsiDisposisiSeeder::class,
            LogDisposisiSeeder::class,
        ]);
    }
}
