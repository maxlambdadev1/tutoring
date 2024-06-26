<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostcodeDbArea extends Model
{

    use HasFactory;
    protected $table = 'postcode_db_area';
    protected $guarded = [];
    
}
