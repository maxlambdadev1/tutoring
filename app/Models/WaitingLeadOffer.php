<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingLeadOffer extends Model
{
    use HasFactory;

    protected $table = 'alchemy_waiting_leads_offer';    
    protected $guarded = [];
        
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
