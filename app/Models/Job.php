<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs';

    protected $fillable = [
        'job_type',
        'replacement_id',
        'parent_id',
        'child_id',
        'date',
        'time',
        'start_date',
        'subject',
        'location',
        'prefered_gender',
        'job_notes',
        'job_status',
        'progress_status',
        'reason',
        'accepted_by',
        'accepted_on',
        'session_id',
        'hidden',
        'source',
        'converted_by',
        '3days_reminder',
        '6days_reminder',
        '9days_reminder',
        'welcome_call',
        'callback_time',
        'create_time',
        'last_updated',
        'last_updated_for_waiting_list',
        'session_type_id',
        'contact_request',
        'vaccinated',
        'experienced_tutor',
        'automation',
        'match_tutor',
        'voucher_number',
        'main_result',
        'performance',
        'attitude',
        'mind',
        'personality',
        'favourite',
        'thirdparty_org_id',
        'special_request_content',
        'special_request_response',
        'tutor_suggested_session_date',
        'is_from_main',
    ];
}
