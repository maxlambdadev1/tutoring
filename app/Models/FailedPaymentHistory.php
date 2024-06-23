<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedPaymentHistory extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_failed_payment_history';

    protected $guarded = [];

}
