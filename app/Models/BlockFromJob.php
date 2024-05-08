<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockFromJob extends Model
{
    use HasFactory;

    protected $table = 'alchemy_block_from_jobs';

    protected $fillable = [
        'tutor_id',
        'not_continue_number',
        'type'
    ];
}
