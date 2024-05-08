<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionStatus extends Model
{
    use HasFactory;

    protected $table = 'alchemy_session_status';

    protected $fillable = [
        'status',
    ];
}
