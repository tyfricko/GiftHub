<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the test user (matej:test123) as mentioned in memory bank
        \App\Models\User::factory()->create([
            'name' => 'Matej Test',
            'username' => 'matej',
            'email' => 'matej@test.com',
            'password' => bcrypt('test123'),
            'email_verified_at' => now(),
        ]);

        // Optionally create additional test users
        // \App\Models\User::factory(5)->create();
    }
}
