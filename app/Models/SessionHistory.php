<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sessions_history';

    protected $fillable = [
        'session_id',
        'author',
        'comment',
        'date',
    ];
}
