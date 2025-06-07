<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\BlogPost;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 blog posts using the factory
        BlogPost::factory(5)->create([
            'status' => 'published',
            'author_id' => 1, // first user is always aidella redac
        ]);
        
        // Switch to a different author for the next set of posts
        BlogPost::factory(2)->create([
            'status' => 'draft',
            'author_id' => 2,
        ]);
    }
}
