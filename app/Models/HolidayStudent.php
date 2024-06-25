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
    
    public function reach_parent() {
        return $this->hasOne(HolidayReachParent::class, 'holiday_id');
    }

    public function tutor() {
        return $this->belongsTo(Tutor::class, 'last_tutor');
    }
    
    public function child() {
        return $this->belongsTo(Child::class);
    }
}
