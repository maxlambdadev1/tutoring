<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockFromJob extends Model
{
    use HasFactory;

    protected $table = 'alchemy_block_from_jobs';
    protected $guarded = [];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
