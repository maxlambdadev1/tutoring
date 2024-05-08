<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    use HasFactory;

    protected $table = 'alchemy_recruiter';

    protected $fillable = [
        'user_id',
        'ABN',
        'bank_account_name',
        'bsb',
        'bank_account_number',
        'id_photo',
        'signature',
        'first_name',
        'last_name',
        'email',
        'phone',
        'pass',
        'stripe_user_id',
        'access_token',
        'stripe_publishable_key',
        'status',
        'logged_status',
        'state',
        'address',
        'suburb',
        'postcode',
        'referral_key',
        'last_updated',
    ];
}
