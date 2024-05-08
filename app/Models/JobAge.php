<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAge extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_age';

    protected $fillable = [
        'job_id',
        'offer',
    ];
}
