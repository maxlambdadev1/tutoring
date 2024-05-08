<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sessions';

    protected $fillable = [
        'type_id',
        'session_status',
        'tutor_id',
        'parent_id',
        'child_id',
        'session_date',
        'session_time',
        'session_subject',
        'session_is_first',
        'session_first_question1',
        'session_first_question2',
        'session_first_question3',
        'session_first_question4',
        'session_length',
        'session_reason',
        'session_price',
        'session_tutor_price',
        'session_third_price',
        'session_penalty',
        'session_charge_time',
        'session_transfer_time',
        'session_charge_id',
        'session_transfer_id',
        'session_invoice_id',
        'session_bill_id',
        'session_manual_marked_paid',
        'session_next_session_id',
        'session_next_session_tutor_date',
        'session_next_session_tutor_time',
        'session_previous_session_id',
        'session_charge_status',
        'session_parent_charge_status',
        'session_overall_rating',
        'session_engagement_rating',
        'session_understanding_rating',
        'session_feedback',
        'session_tutor_notes',
        'session_before_session_reminder',
        'session_after_session_reminder',
        'session_after_session_reminder_18h',
        'session_after_session_reminder_30h',
        'session_after_session_reminder_42h',
        'session_parent_reminder',
        'tax_paid',
        'extra_expenses_paid',
        'request_abn',
        'session_last_changed',
    ];
}
