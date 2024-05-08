<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorOfferVolume extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_offers_volume';

    protected $fillable = [
        'tutor_id',
        'offers',
        'job_ids',
        'step_1',
        'step_2',
        'step_3',
        'hidden',
        'date_lastudate',
    ];
}
