<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayTutorHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_holiday_tutor_history';

    protected $fillable = [
        'holiday_id',
        'author',
        'comment',
        'date',
    ];
}
