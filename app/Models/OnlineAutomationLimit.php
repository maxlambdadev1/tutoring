<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineAutomationLimit extends Model
{
    use HasFactory;

    protected $table = 'alchemy_online_automation_limit';

    protected $fillable = [
        'job_id',
        'tutor_ids',
        'update_avail_status',
        'update_avail_action_handled',
        'last_updated',
    ];
}
