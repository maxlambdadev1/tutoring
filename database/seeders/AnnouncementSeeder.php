<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Announcement;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();
        Announcement::insert([
            ['an_id' => 1, 'an_text' => '', 'who' => 1, 'flag' => 0, 'updated_at' => $now],
            ['an_id' => 2, 'an_text' => '', 'who' => 1, 'flag' => 0, 'updated_at' => $now],
            ['an_id' => 3, 'an_text' => '', 'who' => 1, 'flag' => 0, 'updated_at' => $now],
        ]);
    }
}
