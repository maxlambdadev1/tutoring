<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionReschedule extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sessions_reschedule';

    protected $fillable = [
        'session_id',
        'old_date',
        'old_time',
        'new_date',
        'new_time',
        'hidden',
        'date',
    ];
}
