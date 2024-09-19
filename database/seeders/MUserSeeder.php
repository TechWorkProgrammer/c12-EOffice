<?php

namespace Database\Seeders;

use App\Models\MPejabat;
use App\Models\MUser;
use App\Models\Satminkal;
use Illuminate\Database\Seeder;

class MUserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Pengguna dengan berbagai role (tetap mempertahankan nama user yang sudah ada)
        $users = [
            // Komandan
            ['name' => 'Joko Hasan', 'email' => 'joko@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Komandan Batalyon'],

            // Kasub
            ['name' => 'Budi Santoso', 'email' => 'budi@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Kasub Operasi'],
            ['name' => 'Hendro Wijaya', 'email' => 'hendro@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Kasub Intelijen'],

            // Kabag
            ['name' => 'Agus Salim', 'email' => 'agus@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Kabag Administrasi'],
            ['name' => 'Rudi Hartono', 'email' => 'rudi@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Kabag Keuangan'],
            ['name' => 'Faisal Pratama', 'email' => 'faisal@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Kabag Intel Administrasi'],
            ['name' => 'Yusuf Ahmad', 'email' => 'yusuf@company.com', 'role' => 'Pejabat', 'pejabat_name' => 'Kabag Intel Operasi'],

            // Pelaksana yang sudah ada
            ['name' => 'Pelaksana A1', 'email' => 'pelaksana@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana A1'],

            // Pengguna baru untuk pelaksana tambahan
            ['name' => 'Pelaksana A2', 'email' => 'pelaksanaA2@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana A2'],
            ['name' => 'Pelaksana A3', 'email' => 'pelaksanaA3@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana A3'],
            ['name' => 'Pelaksana A4', 'email' => 'pelaksanaA4@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana A4'],
            ['name' => 'Pelaksana B1', 'email' => 'pelaksanaB1@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana B1'],
            ['name' => 'Pelaksana B2', 'email' => 'pelaksanaB2@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana B2'],
            ['name' => 'Pelaksana B3', 'email' => 'pelaksanaB3@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana B3'],
            ['name' => 'Pelaksana B4', 'email' => 'pelaksanaB4@company.com', 'role' => 'Pelaksana', 'pejabat_name' => 'Pelaksana B4'],

            // Role yang tidak terkait pejabat
            ['name' => 'Tata Usaha', 'email' => 'tatausaha@company.com', 'role' => 'Tata Usaha', 'pejabat_name' => null],
            ['name' => 'Administrator', 'email' => 'admin@company.com', 'role' => 'Administrator', 'pejabat_name' => null],
            ['name' => 'External', 'email' => 'external@company.com', 'role' => 'External', 'pejabat_name' => null]
        ];

        // Buat setiap user berdasarkan array
        foreach ($users as $userData) {
            $pejabatId = null;
            if ($userData['pejabat_name']) {
                $pejabatId = MPejabat::where('name', $userData['pejabat_name'])->first('uuid')->uuid;
            }

            MUser::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => $userData['role'],
                'pejabat_id' => $pejabatId,
                'satminkal_id' => Satminkal::first('uuid')->uuid,
                'password' => bcrypt('password123')
            ]);
        }
    }
}
