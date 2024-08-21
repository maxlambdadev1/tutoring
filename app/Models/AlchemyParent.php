<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Child;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AlchemyParent extends Model
{
    use HasFactory;

    protected $table = 'alchemy_parent';

    protected $guarded = [];
    protected $appends = ['parent_name'];

    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

    public function thirdparty_org()
    {
        return $this->belongsTo(ThirdpartyOrganisation::class, 'thirdparty_org_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function state()
    {
        return $this->belongsTo(State::class, 'parent_state', 'name');
    }

    public function price_parent() {
        return $this->hasOne(PriceParent::class, 'parent_id');
    }
    
    public function price_postcode() {
        return $this->belongsTo(PricePostcode::class, 'parent_postcode');
    }
    
    public function price_tutors() {
        return $this->hasMany(PriceTutor::class, 'parent_id');
    }
    
    public function price_parent_discount() {
        return $this->hasOne(PriceParentDiscount::class, 'parent_id');
    }
    
    public function parent_cc() {
        return $this->hasOne(ParentCc::class, 'parent_id');
    }
    

    public function history() {
        return $this->hasMany(ParentHistory::class, 'parent_id');
    }

    public function getParentNameAttribute()
    {
        return ucfirst($this->parent_first_name) . ' ' . ucfirst($this->parent_last_name);
    }

    public function referralParent()
    {
        return $this->belongsTo(self::class, 'referred_id')->first();
    }

    public function referredParents()
    {
        return $this->hasMany(self::class, 'referred_id');
    }

}
