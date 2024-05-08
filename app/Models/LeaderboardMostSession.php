<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardMostSession extends Model
{
    use HasFactory;

    protected $table = 'alchemy_leaderboard_longest_streak';

    protected $fillable = [
        'tutor_id',
        'tutor_name',
        'tutor_image',
        'session_number',
        'student_number',
    ];
}
