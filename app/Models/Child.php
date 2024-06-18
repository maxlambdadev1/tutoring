<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AlchemyParent;

class Child extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_children';
    protected $appends = ['first_name'];

    protected $guarded = [];

    public function parent() {
        return $this->belongsTo(AlchemyParent::class);
    }

    public function history() {
        return $this->hasMany(ChildHistory::class);
    }
        
    public function getFirstNameAttribute() {
        return explode(' ', $this->child_name)[0]; 
    }
}
