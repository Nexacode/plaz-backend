<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectConversationSummary extends Model
{
    use HasFactory;
    
    protected $table = 'project_conversation_summaries';
    
    protected $fillable = [
    	'text',
    ];
    
    public function todo()
	{
		return $this->hasMany('App\Models\Todo');
	}
}
