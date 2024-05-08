<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'alchemy_tasks';

    protected $fillable = [
        'tutor_id',
        'task_subject',
        'task_name',
        'task_content',
        'task_completed',
        'task_hidden',
        'task_date',
        'task_last_update',
    ];
}
