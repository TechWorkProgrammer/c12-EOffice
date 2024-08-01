<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrashPickup;

class TrashPickupSeeder extends Seeder
{
    public function run()
    {
        TrashPickup::factory()->count(10)->create();
    }
}
