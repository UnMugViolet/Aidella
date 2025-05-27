<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aidella.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@aidella.com',
                'password' => Hash::make(env('APP_PASSWORD')),
                'email_verified_at' => now(),
            ]
        );
    }
}
