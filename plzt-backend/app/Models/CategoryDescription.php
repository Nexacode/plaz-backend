<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDescription extends Model
{
    use HasFactory;
    
    protected $table = 'category_descriptions';
    
    protected $fillable = [
    	'project_id',
    	'category_id',
        'description',
        'user_id',
        'user_name'
    ]; 
        
}
