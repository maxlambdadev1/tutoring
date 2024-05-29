<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplacementTutor extends Model
{
    use HasFactory;

    protected $table = 'alchemy_replacement_tutor';

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(AlchemyParent::class);
    }
    
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
    
    public function replacement_tutor()
    {
        return $this->belongsTo(Tutor::class, 'replacement_tutor_id', 'id');;
    }
    
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
