<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;
    
    protected $table = 'coins';
    
    protected $fillable = [
    	'user_id',
    	'user_name',
		'coin',
		'given'
    ]; 
}
