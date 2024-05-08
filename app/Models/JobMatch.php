<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMatch extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_match';

    protected $fillable = [
        'job_id',
        'distance',
        'no_av',
        'tutors_found',
        'tutor_ids',
        'tutor_ids_full',
        'automation_step',
        'reminder1',
        'update_avail_status',
        'reminder2',
        'change_online_status',
        'reminder3',
        'reminder4',
        'last_updated',
    ];
}
