<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorePricing extends Model
{
    use HasFactory;

    protected $table = 'core_pricing';

    protected $fillable = [
        'session_type_id',
        'session',
        'tutor',
        'increase_rate',
    ];
}
