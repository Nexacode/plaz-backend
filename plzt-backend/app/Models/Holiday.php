<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'todo_event_id',
    	'start',
    	'end',
    	'user_id',
    	'user_name',
    	'contraction',
    	'holidays',
    	'days',
    	'days_left',
    	'year_before',
    	'days_left_year_before',
    	'status',
    	'holiday_year',
    	'color',
    	'from',
    	'to',
    	'signature'
    ];    
    
}
