<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayStudent extends Model
{
    use HasFactory;

    protected $table = 'alchemy_holiday_student';
    protected $guarded = [];
    
    public function history() {
        return $this->hasMany(HolidayStudentHistory::class, 'holiday_id');
    }
}
