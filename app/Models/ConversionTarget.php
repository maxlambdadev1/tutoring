<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionTarget extends Model
{
    use HasFactory;
    
    protected $table = 'alchemy_conversion_target';
    protected $guarded = [];

}
