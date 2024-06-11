<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorApplication extends Model
{
    use HasFactory;
    protected $table = 'alchemy_tutor_application';
    protected $guarded = [];

    public function history() {
        return $this->hasMany(TutorApplicationHistory::class);
    }

}
