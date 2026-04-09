<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Directie',
                'password' => Hash::make('Admin123'),
                'role' => User::ROLE_DIRECTIE,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'vrijwilliger@gmail.com'],
            [
                'name' => 'Vrijwilliger Account',
                'password' => Hash::make('Admin123'),
                'role' => User::ROLE_VRIJWILLIGER,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'magazijn@gmail.com'],
            [
                'name' => 'Magazijn Medewerker',
                'password' => Hash::make('Admin123'),
                'role' => User::ROLE_MAGAZIJNMEDEWERKER,
                'email_verified_at' => now(),
            ]
        );
    }
}
