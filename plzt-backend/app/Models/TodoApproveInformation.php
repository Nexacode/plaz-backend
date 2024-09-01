<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoApproveInformation extends Model
{
    use HasFactory;
    
    protected $table = 'todo_approve_informations';
    
    protected $fillable = [
    	'information',
    	'user_id',
        'user_name',
    ];
}
