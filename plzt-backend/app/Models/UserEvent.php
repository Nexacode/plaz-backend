<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    use HasFactory;
    
    protected $table = 'user_events';

    protected $fillable = [
    	'start_date',
    	'end_date',
    	'duration',
    	'duration_unit',
    	'name',
        'recurrence_rule',
        'event_color',
    ];
}
