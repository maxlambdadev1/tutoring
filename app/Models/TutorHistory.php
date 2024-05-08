<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorHistory extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_history';

    protected $fillable = [
        'tutor_id',
        'author',
        'comment',
        'date',
    ];
}
