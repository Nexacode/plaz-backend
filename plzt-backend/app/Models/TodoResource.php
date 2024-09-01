<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoResource extends Model
{
    use HasFactory;

    protected $table = 'todo_resources';
    
    protected $fillable = [
    	'todo_id',
    	'user_name',
    	'user_id',
    	'unit',
    ];
}
