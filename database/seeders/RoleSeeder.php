<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();
        Role::insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'tutor', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'parent', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'recruiter', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
