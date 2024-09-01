<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;
    
    protected $table = 'milestones';
    
    protected $fillable = [
    	'project_id',
    	'category_id',
        'milestone',
        'description',
        'priority',
        'user_name',
        'user_id',
        'to_category_id',
    ];    
    
    public function todo()
    {
    	return $this->hasMany('App\Models\Todo')->with('assessment')->with('statusname')->withSum('work','time');
    }
    
    public function todotime()
    {
    	return $this->hasMany('App\Models\Todo')->sum('estimated_time');
    }
     
    public function donetodos()
    {
    	return $this->hasMany('App\Models\Todo')->where('status',0);
    }       
    
	public function employee()
	{
		return $this->hasMany('App\Models\Todo')->groupBy('employee');
	}	  
	public function inCategory()
	{
		return $this->hasOne('App\Models\Category','id','category_id');
	}  
    
}
