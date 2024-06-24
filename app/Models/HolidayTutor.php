<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayTutor extends Model
{
    use HasFactory;

    protected $table = 'alchemy_holiday_tutor';

    protected $guarded = [];
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
 
    public function history() {
        return $this->hasMany(HolidayTutorHistory::class, 'holiday_id');
    }

}
