<?php

namespace Database\Seeders;

use App\Models\MPejabat;
use Illuminate\Database\Seeder;

class MPejabatSeeder extends Seeder
{
    public function run(): void
    {
        $strukturPejabat = [
            'Komandan Batalyon' => [
                'Kasub Operasi' => [
                    'Kabag Administrasi' => ['Pelaksana A1', 'Pelaksana A2'],
                    'Kabag Keuangan' => ['Pelaksana A3', 'Pelaksana A4']
                ],
                'Kasub Intelijen' => [
                    'Kabag Intel Administrasi' => ['Pelaksana B1', 'Pelaksana B2'],
                    'Kabag Intel Operasi' => ['Pelaksana B3', 'Pelaksana B4']
                ]
            ]
        ];

        foreach ($strukturPejabat as $komandan => $kasubs) {
            $komandanPejabat = MPejabat::factory()->create(['name' => $komandan, 'atasan_id' => null]);

            foreach ($kasubs as $kasub => $kabags) {
                $kasubPejabat = MPejabat::factory()->create(['name' => $kasub, 'atasan_id' => $komandanPejabat->uuid]);

                foreach ($kabags as $kabag => $pelaksanas) {
                    $kabagPejabat = MPejabat::factory()->create(['name' => $kabag, 'atasan_id' => $kasubPejabat->uuid]);

                    foreach ($pelaksanas as $pelaksana) {
                        MPejabat::factory()->create(['name' => $pelaksana, 'atasan_id' => $kabagPejabat->uuid]);
                    }
                }
            }
        }
    }
}
