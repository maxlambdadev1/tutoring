<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVisit extends Model
{
    use HasFactory;
    protected $table = 'alchemy_jobs_visit';

    protected $guarded = [];

    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }
}
