<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceParent extends Model
{
    use HasFactory;

    protected $table = 'alchemy_price_parent';
    protected  $primaryKey = 'parent_id';
    public $incrementing = false;

    protected $fillable = [
        'parent_id',
        'f2f',
        'online',
    ];
}
