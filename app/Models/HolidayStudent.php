<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayStudent extends Model
{
    use HasFactory;

    protected $table = 'alchemy_holiday_student';

    protected $fillable = [
        'child_id',
        'status',
        'year',
        'last_tutor',
        'reason',
        'date_created',
        'date_last_modified',
    ];
}
