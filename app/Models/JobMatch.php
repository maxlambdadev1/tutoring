<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMatch extends Model
{
    use HasFactory;

    protected $table = 'alchemy_jobs_match';
    protected $guarded = [];

    public function getTutorIdsArrayAttribute() {
        return explode(";", $this->tutor_ids) ?? [];
    }
}
