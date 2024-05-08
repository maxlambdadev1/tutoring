<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoListReminder extends Model
{
    use HasFactory;

    protected $table = 'alchemy_todo_list_reminder';

    protected $fillable = [
        'task_id',
        'followup',
        'email_sent',
    ];
}
