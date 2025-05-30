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
        // Check if the test user exists before updating or creating
        $user = User::where('email', 'redac@aidella.com')->first();

        if (!$user) {
            User::factory()->create([
            'name' => 'Aidella',
            'email' => 'redac@aidella.com',
            ]);
        }
        // Create or update the admin user
        User::updateOrCreate(
            ['email' => 'jaguinpaul@gmail.com'],
            [
                'name' => 'Admin User',
                'email' => 'jaguinpaul@gmail.com',
                'password' => Hash::make(env('APP_PASSWORD')),
                'email_verified_at' => now(),
            ]
        );
    }
}
