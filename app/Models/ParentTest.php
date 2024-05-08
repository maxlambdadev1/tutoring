<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentTest extends Model
{
    use HasFactory;

    protected $table = 'alchemy_parent_test';

    protected $fillable = [
        'user_id',
        'parent_pass',
        'parent_first_name',
        'parent_last_name',
        'parent_email',
        'parent_address',
        'suburb',
        'parent_postcode',
        'parent_state',
        'parent_lat',
        'parent_lon',
        'parent_phone',
        'stripe_customer_id',
        'referrer',
        'heard_from',
        'mailchimp_status',
        'mailchimp_inactive_status',
        '5_sessions_podium_review',
    ];
}
