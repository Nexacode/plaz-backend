<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $table = 'projects';
    
    protected $fillable = [
    	'name',
    	'planing_status',
        'status',
        'deadline',
        'description',
        'color',
        'color_text',
        'color_border',
        'color_background',
        'goal',
        'priority',
        'start',
        'end',
        'duration',
        'duration_unit',
        'cls',
        'direction',
        'manually_scheduled',
        'constraint_type',
        'constraint_date',
        'effort',
        'effort_unit',
        'effort_driven',
        'percent_done',
        'expanded',
        'note',
        'parent_index',
        'scheduling_mode',
        'gantt_status',
        'in_calendar',
        'sub_project',
        'project_id'
    ];
    
	public function parentproject()
    {
        return $this->belongsTo('App\Models\Project', 'project_id','id');
    }
    public function categories()
    {
    	return $this->hasMany('App\Models\Category');
    }

    public function milestones()
    {
    	return $this->hasMany('App\Models\Milestone');
    }

	public function todo()
	{
		return $this->hasMany('App\Models\Todo');
	}  
	
	public function alltodo()
	{
		return $this->hasManyThrough('App\Models\Todo', 'App\Models\Milestone');
	}
	
	public function employee()
	{
		return $this->hasMany('App\Models\Todo');
	}
	
	public function todoopen()
	{
		return $this->hasMany('App\Models\Todo')->where('status',0);
	}
	public function todoopenstatistic()
	{
		return $this->hasMany('App\Models\Todo')->where('todo_status_id','<',7);
	}
	
	public function todoopentime()
	{
		return $this->hasMany('App\Models\Todo')->where('status',0)->sum('calculated_time');
	}
	
	public function tododone()
	{
		return $this->hasMany('App\Models\Todo')->where('status',1);
	}
	public function worktime()
	{
		return $this->hasMany('App\Models\Work')->sum('time');
	}
	public function works()
	{
		return $this->hasMany('App\Models\Work');
	}
	
	public function lasttodo()
	{
		return $this->hasMany('App\Models\Todo')->orderBy('date','DESC')->first();
	}	
	
	public function children()
	{
		return $this->hasMany('App\Models\Todo');
	}			      
       
}
