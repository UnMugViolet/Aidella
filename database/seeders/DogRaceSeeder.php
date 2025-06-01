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
        $races = [
            'Berger Australien',
            'Labrador',
            'Cavalier King Charles',
            'Bouledogue Français',
            'Coton de Tulear',
            'Spitz Nain',
        ];

        foreach ($races as $index => $name) {
            DogRace::create([
                'name' => $name,
                'slug' => strtolower(str_replace(' ', '-', $name)),
                'description' => fake()->sentence(),
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
