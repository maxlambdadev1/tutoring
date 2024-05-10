<?php

namespace Database\Seeders;

use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        Grade::insert([
            ['name' => 'Pre-K', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '3', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '4', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '5', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '6', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '7', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '8', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '9', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '10', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '11', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '12', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
