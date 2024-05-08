<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorFollowup extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_followup';

    protected $fillable = [
        'tutor_id',
        'timestamp',
    ];
}
