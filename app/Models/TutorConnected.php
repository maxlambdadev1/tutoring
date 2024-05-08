<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorConnected extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_connected';

    protected $fillable = [
        'tutor_id',
        'child_id',
        'connected_id',
    ];
}
