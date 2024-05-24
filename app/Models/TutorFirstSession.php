<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorFirstSession extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_first_session';

    protected $guarded = [];
    
}
