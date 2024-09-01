<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoSegment extends Model
{
    protected $table = 'todo_segments';
    
    protected $fillable = [
    	'todo_id',
    	'start',
    	'end',
    	'duration',
    	'duration_unit',
    	'color'
    ];
}
