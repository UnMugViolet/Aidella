<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\DogRace;
use Database\Factories\DogRaceFactory;

class DogRaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DogRace::count() === 0) {
            DogRace::factory()
                ->count(3)
                ->create();
        }
    }
}
