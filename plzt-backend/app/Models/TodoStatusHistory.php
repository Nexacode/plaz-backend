<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'todo_status_histories';
    
    protected $fillable = [
    	'todo_status_id',
    	'user_id',
    	'user_name'
    ]; 
    
}
