<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

	protected $table = 'tickets';

    protected $fillable = [
    	'customer_id',
    	'status',
    	'from_name',
    	'from_email',
    	'subject',
        'priority',
        'type_id',
        'external_project',
    	'text',
    	'solution' 
    ];

	public function workSum()
	{
		return $this->hasMany('App\Models\Work');
	}

	public function works()
	{
		return $this->hasMany('App\Models\Work');
	}

	public function history()
	{
		return $this->hasMany('App\Models\TicketHistory');
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo('App\Models\Customer');
	}
    public function project(): BelongsTo
    {
        return $this->belongsTo('App\Models\Project');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo('App\Models\TicketType');
    }

}
