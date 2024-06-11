<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorApplicationStatus extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_tutor_application_status';
    
    protected $guarded = [];

}
