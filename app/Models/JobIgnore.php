<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobIgnore extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_ignore';

    protected $fillable = [
        'job_id',
        'tutor_id',
    ];
}
