<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoDeadline extends Model
{
    use HasFactory;
    
    protected $table = 'todo_deadlines';
    
    protected $fillable = [
    	'todo_id',
    	'todo_status_id',
    	'description',
    	'deadline'
    ];
}
