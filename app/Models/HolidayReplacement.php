<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayReplacement extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_holiday_replacement';
    protected $guarded = [];

    public function history() {
        return $this->hasMany(HolidayReplacementHistory::class, 'holiday_id');
    }

    public function replacement_tutor () {
        return $this->belongsTo(ReplacementTutor::class, 'replacement_id');
    }

}
