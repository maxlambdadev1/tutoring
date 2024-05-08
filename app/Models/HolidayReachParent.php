<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayReachParent extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_holiday_reach_parent';

    protected $fillable = [
        'holiday_id',
        'sms_first',
        'email_first',
        'sms_second',
        'email_second',
        'link',
    ];
}
