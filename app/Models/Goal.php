<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_goal';

    protected $fillable = [
        'year',
        'quarter',
        'month',
        'goal_start',
        'goal_current',
    ];
}
