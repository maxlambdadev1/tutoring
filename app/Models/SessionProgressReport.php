<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionProgressReport extends Model
{
    use HasFactory;

    protected $table = 'alchemy_session_progress_report';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'session_count',
        'last_session',
        'unique_key',
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'review_reminder',
        'reminder_count',
        'submitted_on',
        'date_lastupdated',
    ];
}
