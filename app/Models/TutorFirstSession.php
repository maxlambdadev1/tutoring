<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorFirstSession extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_first_session';

    protected $fillable = [
        'tutor_id',
        'status',
        'followup',
        'call_date',
        'email_sent',
        'followup_sent',
        'date_created',
        'date_last_updated',
    ];
}
