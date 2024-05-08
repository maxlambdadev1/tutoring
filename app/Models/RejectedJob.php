<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedJob extends Model
{
    use HasFactory;

    protected $table = 'alchemy_rejected_jobs';

    protected $fillable = [
        'tutor_id',
        'job_ids',
    ];
}
