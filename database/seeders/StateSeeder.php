<?php

namespace Database\Seeders;

use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        State::insert([
            ['name' => 'NSW', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'VIC', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'QLD', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
