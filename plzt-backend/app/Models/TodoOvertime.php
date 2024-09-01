<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoOvertime extends Model
{
    use HasFactory;

	protected $table = 'todo_overtimes';

    protected $fillable = [
    	'todo_id',
    	'estimation',
    	'information',
    	'user_id',
    	'user_name',
    ];
}
