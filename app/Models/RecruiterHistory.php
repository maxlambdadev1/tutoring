<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruiterHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_recruiter_history';

    protected $fillable = [
        'recruiter_id',
        'author',
        'comment',
        'date',
    ];
}
