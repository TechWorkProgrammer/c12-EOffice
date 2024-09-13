<?php

namespace Database\Seeders;

use App\Models\MPejabat;
use App\Models\MUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MUser::factory()->create([
            'name' => 'Joko Hasan',
            'email' => 'joko@company.com',
            'role' => 'Pejabat',
            'pejabat_id' => MPejabat::where('name', 'Komandan A')->get()->uuid,
        ]);

        MUser::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@company.com',
            'role' => 'Pejabat',
            'pejabat_id' => MPejabat::where('name', 'Kasub A')->get()->uuid,
        ]);

        MUser::factory()->create([
            'name' => 'Agus Salim',
            'email' => 'agus@company.com',
            'role' => 'Pejabat',
            'pejabat_id' => MPejabat::where('name', 'Kabag A')->get()->uuid,
        ]);

        MUser::factory()->create([
            'name' => 'Tata usaha',
            'email' => 'tatausaha@company.com',
            'role' => 'Tata Usaha',
        ]);

        MUser::factory()->create([
            'name' => 'Pelaksana',
            'email' => 'pelaksana@company.com',
            'role' => 'Pelaksana',
            'pejabat_id' => MPejabat::where('name', 'Kabag A')->get()->uuid,
        ]);

        MUser::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@company.com',
            'role' => 'Administrator',
        ]);

        MUser::factory()->create([
            'name' => 'External',
            'email' => 'external@company.com',
            'role' => 'External',
        ]);
    }
}
