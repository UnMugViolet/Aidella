<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostCategory>
 */
class PostCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories_name = [
            'entretien',
            'alimentation',
            'comportement',
            'santé',
            'éducation',
            'loisirs',
            'accessoires',
            'adoption',
            'general'
        ];

        $name = $this->faker->unique()->randomElement($categories_name);

        return [
            'name' => $name,
            'description' => $this->faker->sentence,
            'slug' => Str::slug($name, '-', 'fr'),
        ];
    }
}
