<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
	use HasFactory;

	protected $table = 'ticket_histories';
    
    protected $fillable = [
    	'ticket_id',
    	'ticket_status_id',
    	'user_id',
    	'user_name'
    ];
}
