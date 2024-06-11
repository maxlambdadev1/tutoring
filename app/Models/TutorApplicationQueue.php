<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorApplicationQueue extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_tutor_application_queue';
    
    protected $guarded = [];
}
