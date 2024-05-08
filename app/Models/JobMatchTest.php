<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMatchTest extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_match_test';

    protected $fillable = [
        'job_id',
        'distance',
        'no_av',
        'tutors_found',
        'tutor_ids',
        'reminder1',
        'reminder2',
        'reminder3',
        'last_updated',
    ];
}
