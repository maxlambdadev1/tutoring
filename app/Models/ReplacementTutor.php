<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplacementTutor extends Model
{
    use HasFactory;

    protected $table = 'alchemy_replacement_tutor';

    protected $fillable = [
        'tutor_id',
        'parent_id',
        'child_id',
        'last_session',
        'job_id',
        'replacement_tutor_id',
        'replacement_status',
        'tutor_last_session',
        'tutor_notes',
        'tutor_link',
        'parent_day',
        'parent_time',
        'parent_notes',
        'parent_link',
        'date_added',
        'last_modified',
    ];
}
