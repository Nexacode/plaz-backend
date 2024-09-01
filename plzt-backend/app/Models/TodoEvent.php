<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoEvent extends Model
{
    protected $table = 'todo_events';
    
    protected $fillable = [
    	'project_id',
    	'milestone_id',
    	'todo_id',
    	'category_id',
		'start',
		'end',
		'start_date',
		'end_date',
		'hours',
		'minutes',
		'color',
		'color_text',
		'color_border',
		'user_id',
		'title'
    ];
    
    public function todo()
    {
    	return $this->belongsTo('App\Models\Todo');
    }    
    
}
