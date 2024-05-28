<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory, SoftDeletes;

    private $profile_path = 'uploads/admin/profile';
    protected $guarded = [];

    public function getPhoto()
    {
        return $this->photo ? "storage/" . $this->photo : 'images/no_avatar.jpg';
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin_role()
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }
    public function storeAdmin($request)
    {
        if ($request['user_id']) $this->user_id = $request['user_id'];
        
        $this->admin_name = $request['first_name'] . " " .  $request['last_name'];
        $this->phone = $request['phone'];
        $this->admin_role_id = $request['admin_role_id'];
        $this->photo = $request['photo'];
        
        $this->save();
        return $this;
    }

    public function getFirstName() {
        return explode(' ', $this->admin_name)[0]; 
    }
}
