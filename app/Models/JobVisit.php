<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVisit extends Model
{
    use HasFactory;
    protected $table = 'alchemy_jobs_visit';

    protected $fillable = [
        'job_id',
        'tutor_id',
        'cnt',
    ];
}
