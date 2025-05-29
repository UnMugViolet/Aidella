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
        
        return [
            'name' => $this->faker->unique()->randomElement($races),
            'description' => fake()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
