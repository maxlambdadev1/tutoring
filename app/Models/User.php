<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_role()
    {
        return $this->belongsTo(Role::class, 'role');
    }

    public function hasRole($role)
    {
        return $this->user_role->name === $role;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function storeUser($body)
    {
        $this->email = $body['email'];
        $this->password = bcrypt($body['password']);
        $this->role = $body['role'];
        $this->save();

        return $this;
    }
}
