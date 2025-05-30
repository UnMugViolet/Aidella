<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DogRace>
 */
class DogRaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $races = [
            'Berger Australien',
            'Labrador',
            'Cavalier King Charles',
            'Bouledogue Français',
            'Coton de Tulear',
            'Spitz Nain',
        ];

        $order = 0;
        
        $name = $this->faker->unique()->randomElement($races);

        return [
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'description' => fake()->sentence(),
            'order' =>  $order++,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
