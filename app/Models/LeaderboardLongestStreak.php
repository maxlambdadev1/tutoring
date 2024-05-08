<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardLongestStreak extends Model
{
    use HasFactory;

    protected $table = 'alchemy_leaderboard_longest_streak';

    protected $fillable = [
        'tutor_id',
        'tutor_name',
        'tutor_image',
        'week_number',
        'student_name',
    ];
}
