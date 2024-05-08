<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\AdminRole;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();
        AdminRole::insert([
            ['id' => 1, 'name' => 'owner', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'manager', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'collaborator', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
