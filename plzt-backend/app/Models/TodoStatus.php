<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoStatus extends Model
{
    use HasFactory;
    
    protected $table = 'todo_statuses';

    protected $fillable = [
    	'name',
    ];     
}
