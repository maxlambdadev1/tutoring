<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorInactiveSchedule extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_inactive_schedule';
    protected $guarded = [];
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

}
