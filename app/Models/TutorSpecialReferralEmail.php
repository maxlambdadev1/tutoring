<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSpecialReferralEmail extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_special_referral_email';

    protected $fillable = [
        'tutor_id',
        'reminder1',
        'reminder2',
        'reminder3',
    ];
}
