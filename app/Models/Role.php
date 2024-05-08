<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected $table = 'alchemy_user_role';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
