<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVolumeCount extends Model
{
    use HasFactory;
    protected $table = 'alchemy_jobs_volume_count';

    protected $fillable = [
        'job_id',
        'tutor_id',
        'date',
    ];
}
