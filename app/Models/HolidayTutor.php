<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayTutor extends Model
{
    use HasFactory;

    protected $table = 'alchemy_holiday_tutor';

    protected $fillable = [
        'tutor_id',
        'status',
        'year',
        'url',
        'date_created',
        'date_last_modified',
    ];
}
