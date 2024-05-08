<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationFeeHistory extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_cancellation_fee_history';

    protected $fillable = [
        'cancellation_id',
        'author',
        'comment',
        'date'
    ];

}
