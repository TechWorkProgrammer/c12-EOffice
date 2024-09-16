<?php

namespace Database\Seeders;

use App\Models\MPejabat;
use App\Models\MUser;
use App\Models\Satminkal;
use Illuminate\Database\Seeder;

class MUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MUser::create([
            'name' => 'Joko Hasan',
            'email' => 'joko@company.com',
            'role' => 'Pejabat',
            'pejabat_id' => MPejabat::where('name', 'Komandan A')->first('uuid')->uuid,
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);

        MUser::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@company.com',
            'role' => 'Pejabat',
            'pejabat_id' => MPejabat::where('name', 'Kasub A')->first('uuid')->uuid,
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);

        MUser::create([
            'name' => 'Agus Salim',
            'email' => 'agus@company.com',
            'role' => 'Pejabat',
            'pejabat_id' => MPejabat::where('name', 'Kabag A')->first('uuid')->uuid,
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);

        MUser::create([
            'name' => 'Tata usaha',
            'email' => 'tatausaha@company.com',
            'role' => 'Tata Usaha',
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);

        MUser::create([
            'name' => 'Pelaksana',
            'email' => 'pelaksana@company.com',
            'role' => 'Pelaksana',
            'pejabat_id' => MPejabat::where('name', 'Kabag A')->first('uuid')->uuid,
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);

        MUser::create([
            'name' => 'Administrator',
            'email' => 'admin@company.com',
            'role' => 'Administrator',
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);

        MUser::create([
            'name' => 'External',
            'email' => 'external@company.com',
            'role' => 'External',
            'satminkal_id' => Satminkal::first('uuid')->uuid,
            'password' => bcrypt('password123')
        ]);
    }
}
