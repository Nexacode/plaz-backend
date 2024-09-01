<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoAssessment extends Model
{
    use HasFactory;
    
    protected $table = 'todo_assessments';
    
    protected $fillable = [
    	'project_id',
    	'categorie_id',
    	'milestone_id',
    	'todo_id',
        'time',
        'user_id',
        'user_name'
    ]; 
    
    public function todo()
	{
		return $this->belongsTo('App\Models\Todo');
	}
}
