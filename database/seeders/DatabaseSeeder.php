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
        ]);

        \App\Models\Admin::factory(11)->create();
        \App\Models\Tutor::factory(11)->create();
        \App\Models\User::factory(10)->create();
    }
}
