<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobReschedule extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_reschedule';

    protected $fillable = [
        'job_id',
        'tutor_id',
        'date',
        'time',
        'last_updated',
    ];
}
