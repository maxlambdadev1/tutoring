<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTest extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_test';

    protected $fillable = [
        'job_type',
        'replacement_id',
        'parent_id',
        'child_id',
        'date',
        'time',
        'start_date',
        'subject',
        'location',
        'prefered_gender',
        'job_notes',
        'job_status',
        'reason',
        'accepted_by',
        'accepted_on',
        'session_id',
        'hidden',
        'source',
        'converted_by',
        '3days_reminder',
        '6days_reminder',
        '9days_reminder',
        'welcome_call',
        'create_time',
        'last_updated',
    ];
}
