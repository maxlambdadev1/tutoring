<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_history';

    protected $fillable = [
        'job_id',
        'author',
        'comment',
        'date',
    ];
}
