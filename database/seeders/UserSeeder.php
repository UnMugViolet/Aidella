<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Create or update the admin user
        User::updateOrCreate(
            ['email' => 'admin@aidella.com'],
            [
                'name' => 'Admin User',
                'email' => 'jaguinpaul@gmail.com',
                'password' => Hash::make(env('APP_PASSWORD')),
                'email_verified_at' => now(),
            ]
        );
    }
}
