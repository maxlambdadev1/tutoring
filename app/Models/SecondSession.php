<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondSession extends Model
{
    use HasFactory;

    protected $table = 'alchemy_second_session';

    protected $fillable = [
        'tutor_id',
        'child_id',
        'parent_id',
        'unique_link',
        'second_session',
        'second_session_id',
        'payment_details',
    ];
}
