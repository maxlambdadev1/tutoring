<?php

namespace Database\Seeders;

use App\Models\Availability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        $data = json_encode(['7:30 AM', '9:00 AM', '10:30 AM', '12:00 PM', '1:15 PM', '2:30 PM', '3:50 PM', '5:10 PM', '6:30 PM', '7:45 PM']);
        Availability::insert([
            ['name' => 'MONDAY', 'short_name' => 'mon', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'TUESDAY', 'short_name' => 'tue', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'WEDNESDAY', 'short_name' => 'wed', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'THURSDAY', 'short_name' => 'thu', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'FRIDAY', 'short_name' => 'fri', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SATURDAY', 'short_name' => 'sat', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SUNDAY', 'short_name' => 'sun', 'time' => $data, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
