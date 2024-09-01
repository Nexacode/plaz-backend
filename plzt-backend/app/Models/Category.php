<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = [
    	'project_id',
    	'in_category_id',
        'name',
        'start',
        'end',
        'start_date',
        'end_date',
        'color',
        'color_text',
        'color_border',
        'color_background',
        'returning',
        'done'
    ];  
    
	public function description()
    {
    	return $this->hasMany('App\Models\CategoryDescription');
    } 
    
	public function todo()
    {
    	return $this->hasMany('App\Models\Todo');
    }     
    
}
