<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AlchemyParent;

class Child extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_children';
    protected $primaryKey = 'children_id';

    protected $guarded = [];

    public function parent() {
        return $this->belongsTo(AlchemyParent::class, 'parent_id');
    }

}
