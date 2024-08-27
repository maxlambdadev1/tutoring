<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassdoorReview extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_glassdoor_review';
    
    protected $guarded = [];
    
    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }

}
