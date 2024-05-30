<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'alchemy_sessions';
    protected $guarded = [];
    
    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }
    
    public function parent() {
        return $this->belongsTo(AlchemyParent::class);
    }
    
    public function child() {
        return $this->belongsTo(Child::class);
    }
}
