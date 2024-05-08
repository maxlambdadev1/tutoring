<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayReplacement extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_holiday_replacement';

    protected $fillable = [
        'child_id',
        'year',
        'replacement_id',
        'date_created',
        'date_last_modified',
    ];
}
