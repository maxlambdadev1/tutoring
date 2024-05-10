<?php

namespace Database\Seeders;

use App\Models\SessionType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SessionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        SessionType::insert([
            ['name' => 'Face To Face', 'kind' => 'normal', 'session_price' => '69', 'tutor_price' => '35', 'increase_rate' => '7', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Online', 'kind' => 'normal', 'session_price' => '54', 'tutor_price' => '30', 'increase_rate' => '5', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Workshop Face To Face', 'kind' => 'creative', 'session_price' => '100', 'tutor_price' => '60', 'increase_rate' => '0', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Workshop Online', 'kind' => 'creative', 'session_price' => '90', 'tutor_price' => '50', 'increase_rate' => '0', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
