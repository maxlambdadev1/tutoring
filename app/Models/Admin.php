<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    private $profile_path = 'uploads/admin/profile';
    protected $guarded = [];

    public function getPhoto()
    {
        return $this->photo ? $this->photo : 'images/no_avatar.jpg';
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin_role()
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }
}
