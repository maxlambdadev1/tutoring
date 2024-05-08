<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingLeadOffer extends Model
{
    use HasFactory;

    protected $table = 'alchemy_waiting_leads_offer';

    protected $fillable = [
        'job_id',
        'tutor_id',
        'date',
        'status',
        'reminder',
    ];
}
