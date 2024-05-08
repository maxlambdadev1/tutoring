<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    use HasFactory;

    protected $table = 'alchemy_todo_list';

    protected $fillable = [
        'task_status',
        'assigned_to',
        'description',
        'created_by',
        'followup',
        'due_date',
        'email_sent',
        'created_at',
    ];
}
