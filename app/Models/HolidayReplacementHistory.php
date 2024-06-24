<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayReplacementHistory extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_holiday_replacement_history';
    protected $guarded = [];
    
}
