<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorWwccValidate extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_wwcc_validate';
    protected $guarded = [];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
