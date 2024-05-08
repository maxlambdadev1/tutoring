<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorReview extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_review';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'progress_report_id',
        'rating',
        'rating_comment',
        'approved',
        'emailed',
        'hidden',
        'reject',
        'type',
        'date_lastupdated',
    ];
}
