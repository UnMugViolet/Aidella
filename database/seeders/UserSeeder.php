<?php

namespace Database\Seeders;

use App\Models\User;
use Orchid\Platform\Models\Role;
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
        $admin = User::updateOrCreate(
            ['email' => 'jaguinpaul@gmail.com'],
            [
                'name' => 'Admin User',
                'email' => 'jaguinpaul@gmail.com',
                'password' => Hash::make(env('APP_PASSWORD')),
                'permissions' => [
                    "platform.index" => true,
                    "platform.systems.roles" => true,
                    "platform.systems.users" => true,
                    "platform.systems.attachment" => true,
                ],
                'email_verified_at' => now(),
            ]
        );

        // Assign the 'admin' role to the admin user
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $admin->addRole($adminRole);
        }
    }
}
