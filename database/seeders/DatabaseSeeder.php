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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            AdminRoleSeeder::class,
            AdminSeeder::class,
            GradeSeeder::class,
            StateSeeder::class,
            AvailabilitySeeder::class,
            SessionTypeSeeder::class,
            SubjectSeeder::class,
        ]);

        \App\Models\Admin::factory(11)->create();
        \App\Models\Tutor::factory(11)->create();
        \App\Models\User::factory(10)->create();
    }
}
