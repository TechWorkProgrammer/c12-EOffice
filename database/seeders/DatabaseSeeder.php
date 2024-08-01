<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            QuestionerSeeder::class,
            QuestionerUserSeeder::class,
            PojokEdukasiSeeder::class,
            PojokEdukasiContentSeeder::class,
            ProgramSeeder::class,
            ProgramContentSeeder::class,
            TrashPickupSeeder::class,
            HadiahSeeder::class,
            HistoryPointSeeder::class,
        ]);
    }
}
