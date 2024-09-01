<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoRework extends Model
{
    use HasFactory;
    
    protected $table = 'todo_reworks';
    
    protected $fillable = [
    	'todo_id',
    	'description',
    	'user_id',
    	'user_name'
    ]; 
}
