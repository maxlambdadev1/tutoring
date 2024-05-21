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

    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

    public function getParentName()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function referralParent()
    {
        return $this->belongsTo(self::class, 'referred_id')->first();
    }

    public function referredParents()
    {
        return $this->hasMany(self::class, 'referred_id');
    }

    public function getParentNameAttribute() {
        return $this->parent_first_name . " " . $this->parent_last_name;
    }
}
