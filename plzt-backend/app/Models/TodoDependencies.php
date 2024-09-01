<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoDependencies extends Model
{
    use HasFactory;
    
    protected $table = 'todo_dependencies';
    
    protected $fillable = [
    	'from',
    	'to',
    	'cls',
    ];
    
}
