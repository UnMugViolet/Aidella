<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'meta_title' => $this->faker->sentence,
            'meta_description' => $this->faker->text(160),
            'slug' => $this->faker->unique()->slug,
            'author_id' => $this->faker->numberBetween(1, 2),
            'category_id' => $this->faker->numberBetween(1, 4),
            'dog_race_id' => $this->faker->numberBetween(1, 6),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
