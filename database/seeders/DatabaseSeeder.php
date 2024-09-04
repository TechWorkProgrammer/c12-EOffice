<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Prize;
use App\Models\Program;
use App\Models\ProgramContent;
use App\Models\Questioner;
use App\Models\QuestionerUser;
use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Rafa',
                'phone_number' => '081234567890',
                'password' => bcrypt('Rafa'),
                'address' => 'Jl. Mawar No. 1',
                'email' => 'rafa@example.com',
                'birthdate' => '2000-01-01',
                'role' => User::ROLE_USER,
                'is_verified' => true,
            ],
            [
                'name' => 'John',
                'phone_number' => '081234567891',
                'password' => bcrypt('Driver'),
                'address' => 'Jl. Melati No. 2',
                'email' => 'driver@example.com',
                'birthdate' => '1995-01-01',
                'role' => User::ROLE_DRIVER,
                'is_verified' => true,
            ],
            [
                'name' => 'Admin',
                'phone_number' => 'Admin',
                'password' => bcrypt('Admin'),
                'address' => 'Jl. Kenanga No. 3',
                'email' => 'admin@example.com',
                'birthdate' => '1985-01-01',
                'role' => User::ROLE_ADMIN,
                'is_verified' => true,
                'point' => 1000
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['phone_number' => $userData['phone_number']],
                $userData
            );
        }

        $questioners = [
            [
                'question' => 'Apakah Anda puas dengan layanan kami?',
                'is_active' => true,
            ],
            [
                'question' => 'Apakah Anda akan merekomendasikan layanan kami kepada teman?',
                'is_active' => true,
            ],
            [
                'question' => 'Apa yang bisa kami tingkatkan?',
                'is_active' => false,
            ],
        ];

        foreach ($questioners as $questionerData) {
            Questioner::updateOrCreate(
                ['question' => $questionerData['question']],
                $questionerData
            );
        }

        $users = User::all();
        $questioners = Questioner::all();

        foreach ($users as $user) {
            foreach ($questioners as $questioner) {
                QuestionerUser::updateOrCreate(
                    [
                        'questioner_id' => $questioner->uuid,
                        'user_id' => $user->uuid,
                    ],
                    [
                        'answer' => rand(1, 5)
                    ]
                );
            }
        }

        Server::updateOrCreate(
            ['id' => 1],
            [
                'longitude' => 112.610807,
                'latitude' => -7.954666,
            ]
        );

        $educations = [
            [
                'title' => 'Prosedur Bimbingan Akademik',
                'link' => 'https://youtu.be/cYvKniM6-Qg?si=ljUTulLg2PTyegag',
            ],
            [
                'title' => 'Virtual Sport',
                'link' => 'https://youtu.be/mrSVWaI17Es?si=mRIvDCpRmHkDQRg7',
            ],
            [
                'title' => 'Profil Laboratorium Robotika',
                'link' => 'https://youtu.be/WVOWij_J4y8?si=8kmOKyeDnRPyCUOR',
            ],
        ];

        foreach ($educations as $educationData) {
            Education::create($educationData);
        }

        $admin = User::where('role', User::ROLE_ADMIN)->first();

        if ($admin) {
            $program = Program::create([
                'name' => 'Webinar Filkom UB',
                'image' => 'https://filkom.ub.ac.id/wp-content/uploads/2023/06/1-11.jpg',
                'created_by' => $admin->uuid,
            ]);

            $programContents = [
                'https://youtu.be/PhDsKipdNYU?si=TXDjbqnUq7nvewBL',
                'https://youtu.be/Xu1TmCNNMDM?si=1kf-FU88iTkxpTEO',
                'https://youtu.be/zs5neUpYnf4?si=TQGU4AsiaQ_xVabg',
                'https://youtu.be/9ybwgK60-O8?si=dx4PQpPiArJpfaW5',
                'https://youtu.be/VHI9_24q5Dc?si=-SSv_zGuSzhvD6qb',
                'https://youtu.be/k3t3pMBaWrU?si=Bk7E5D52UzaB0vz5',
                'https://youtu.be/5W1IYtNEkSY?si=WgOikHC4PZCAB3Pm',
            ];

            foreach ($programContents as $link) {
                ProgramContent::create([
                    'program_id' => $program->uuid,
                    'name' => 'Webinar Filkom UB',
                    'link' => $link,
                ]);
            }
        }

        $prizes = [
            [
                'name' => 'Beras',
                'possibility' => 20.0,
            ],
            [
                'name' => 'Minyak Goreng',
                'possibility' => 15.0,
            ],
            [
                'name' => 'Gula',
                'possibility' => 10.0,
            ],
            [
                'name' => 'Tepung Terigu',
                'possibility' => 10.0,
            ],
            [
                'name' => 'Kopi',
                'possibility' => 10.0,
            ],
            [
                'name' => 'Susu Bubuk',
                'possibility' => 10.0,
            ],
            [
                'name' => 'Mie Instan',
                'possibility' => 15.0,
            ],
            [
                'name' => 'Telur Ayam',
                'possibility' => 10.0,
            ],
        ];

        foreach ($prizes as $prize) {
            Prize::updateOrCreate(
                ['name' => $prize['name']],
                $prize
            );
        }
    }
}
