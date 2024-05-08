<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationFee extends Model
{
    use HasFactory;

    protected $table = 'alchemy_cancellation_fee';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'session_id',
        'session_date',
        'reason',
        'bill_id',
        'invoice_id',
        'stripe_charge_id',
        'stripe_charge_time',
        'stripe_transfer_id',
        'status',
        'date_submitted',
        'date_last_updated'
    ];
}
