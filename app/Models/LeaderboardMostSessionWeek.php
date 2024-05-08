<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardMostSessionWeek extends Model
{
    use HasFactory;

    protected $table = 'alchemy_leaderboard_most_sessions_week';

    protected $fillable = [
        'tutor_id',
        'tutor_name',
        'tutor_image',
        'session_number',
        'last_session_date',
    ];
}
