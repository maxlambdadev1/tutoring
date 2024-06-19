<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdpartyOrganisation extends Model
{
    use HasFactory;

    protected $table = 'alchemy_thirdparty_organisation';

    protected $guarded = [];
    
}
