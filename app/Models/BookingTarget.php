<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTarget extends Model
{
    use HasFactory;

    protected $table = 'alchemy_booking_target';

    protected $fillable = [
        'job_id',
        'source',
        'booking_date'
    ];
}
