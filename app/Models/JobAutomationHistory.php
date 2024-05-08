<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAutomationHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_automation_history';

    protected $fillable = [
        'job_id',
        'author',
        'comment',
        'date',
    ];
}
