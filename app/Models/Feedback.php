<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_feedback';

    protected $fillable = [
        'user_id',
        'user_type',
        'summary',
        'feedback',
        'type',
        'mood',
        'referer',
        'date',
    ];
}
