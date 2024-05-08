<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionFilter extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sessions_filter';

    protected $fillable = [
        'session_id',
        'tutor_id',
        'parent_id',
        'child_id',
        'followup_timestamp',
        'filter',
    ];
}
