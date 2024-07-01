<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_offer';
    protected  $primaryKey = 'job_id';

    protected $guarded = [];
    
}
