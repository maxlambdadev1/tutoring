<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRejectHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_reject_history';

    protected $fillable = [
        'job_id',
        'tutor_id',
        'author',
        'comment',
        'date',
    ];
}
