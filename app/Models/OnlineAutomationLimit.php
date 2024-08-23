<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineAutomationLimit extends Model
{
    use HasFactory;

    protected $table = 'alchemy_online_automation_limit';
    protected $guarded = [];

    public function getTutorIdsArrayAttribute() {
        return explode(';', $this->tutor_ids) ?? [];
    }
}
