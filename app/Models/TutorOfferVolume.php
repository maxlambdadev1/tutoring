<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorOfferVolume extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tutor_offers_volume';

    protected $guarded = [];

    public function getJobIdsArrayAttribute() {
        $job_ids = trim($this->job_ids, ";");
        return explode(";", $job_ids) ?? [];
    }

}
