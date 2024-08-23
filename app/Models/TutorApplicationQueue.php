<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorApplicationQueue extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_tutor_application_queue';    
    protected $guarded = [];
    protected  $primaryKey = 'app_id';


    public function tutor_application()
    {
        return $this->belongsTo(TutorApplication::class, 'app_id');
    }
}
