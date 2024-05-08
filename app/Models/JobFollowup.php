<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFollowup extends Model
{
    use HasFactory;

    protected $table = 'alchemy_job_followup';

    protected $fillable = [
        'tutor_id',
    ];
}
