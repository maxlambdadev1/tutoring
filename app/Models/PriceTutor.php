<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Trait\ModelSelectable;

class PriceTutor extends Model
{
    use HasFactory, ModelSelectable;

    protected $table = 'alchemy_price_tutor';
    protected $guarded = [];
    
    public function parent()
    {
        return $this->belongsTo(AlchemyParent::class);
    }
    
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }
    
}
