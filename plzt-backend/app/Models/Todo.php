<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    
    protected $table = 'todos';
    
    protected $fillable = [
    	'project_id',
    	'milestone_id',
    	'category_id',
        'todo',
        'discussed',
        'date',
        'estimated_time',
        'calculated_time',
        'deadline',
        'status',
        'temporary',
        'online_test',
        'online_live',
        'description',
        'evaluation_information',
        'user_id',
        'user_name',
        'active',
        'priority',
        'start',
        'end',
        'color',
        'checking_status',
        'main_category_id',
        'todo_status_id',
        'duration',
        'duration_unit',
        'effort',
        'effort_unit',
        'effort_driven',
        'note',
        'percent_done',
        'constraint_date',
        'constraint_type',
        'early_start_date',
        'early_end_date',
        'parent_id',
        'parent_index',
        'ordered_parent_index',
        'external_information'
    ]; 
    
	public function work()
	{
		return $this->hasMany('App\Models\Work');
	}
	public function workSum()
	{
		return $this->hasMany('App\Models\Work');
	}	
	
	public function assessment()
	{
		return $this->hasMany('App\Models\TodoAssessment');
	}

	public function calendarevents()
	{
		return $this->hasMany('App\Models\TodoEvent');
	}	

	public function assessmentSum()
	{
		return $this->hasMany('App\Models\TodoAssessment','todo_id','id');
	}
	
	public function project()
	{
		return $this->belongsTo('App\Models\Project');
	}
	public function milestone()
	{
		return $this->belongsTo('App\Models\Milestone');
	}
	
	public function lastAssessment()
	{
		return $this->hasOne('App\Models\TodoAssessment')->latest();
	}

	public function events()
	{
		return $this->hasMany('App\Models\TodoEvent');
	}
	
	public function segments()
	{
		return $this->hasMany('App\Models\TodoSegment');
	}
	
	public function histories()
	{
		return $this->hasMany('App\Models\TodoStatusHistory');
	}	

	public function statusname()
	{
		return $this->hasOne('App\Models\TodoStatus','id','todo_status_id');
	}
	
	public function resources()
	{
		return $this->hasMany('App\Models\TodoResources');
	}
	
	public function overtimes()
	{
		return $this->hasMany('App\Models\TodoOvertime');
	}
	
	public function reworks()
	{
		return $this->hasMany('App\Models\TodoReworks');
	}
	public function deadlines()
	{
		return $this->hasMany('App\Models\TodoDeadline');
	}
	public function deadline()
	{
		return $this->hasOne('App\Models\TodoDeadline')->latestOfMany();
	}
	public function lastDeadline()
	{
		return $this->hasOne('App\Models\TodoDeadline')->latest();
	}
	public function categories()
	{
		return $this->hasMany('App\Models\TodoCategory');
	}
	public function approveInformations()
	{
		return $this->hasMany('App\Models\TodoApproveInformation');
	}
}
