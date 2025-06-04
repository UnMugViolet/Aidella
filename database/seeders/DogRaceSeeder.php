<?php

namespace Database\Seeders;

use App\Models\BlogPost;
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
            $dogRace = DogRace::create([
                'name' => $name,
                'description' => fake()->sentence(),
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create related BlogPost
            BlogPost::create([
                'dog_race_id' => $dogRace->id,
                'slug' => strtolower(str_replace(' ', '-', $name)),
                'title' => 'Le ' . $dogRace->name,
                'status' => 'published',
                'content' => fake()->paragraph(),
                'meta_title' => 'La page du ' . $dogRace->name,
                'meta_description' => fake()->text(160),
                'dog_race_id' => $dogRace->id,
                'author_id' => fake()->numberBetween(1, 2),
                'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
