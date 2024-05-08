<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstSessionTarget extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_first_session_target';

    protected $fillable = [
        'session_id',
        'session_date',
    ];
}
