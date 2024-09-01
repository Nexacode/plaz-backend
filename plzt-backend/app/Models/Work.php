<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;
    
    protected $table = 'works';
    
    protected $fillable = [
    	'project_id',
    	'categorie_id',
    	'milestone_id',
    	'todo_id',
    	'ticket_id',
        'time',
        'comment',
        'client',
        'user_id',
        'user_name',
        'date'
    ]; 
    
	public function todo()
	{
		return $this->belongsTo('App\Models\Todo');
	}  
	
	public function project()
	{
		return $this->belongsTo('App\Models\Project');
	} 
	
	public function category()
	{
		return $this->belongsTo('App\Models\Category','categorie_id','in_category_id');
	} 	
	
	public function assessment()
	{
		return $this->belongsTo('App\Models\TodoAssessment','todo_id','todo_id');
	} 	      
    
}
