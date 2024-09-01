<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Todo;
use App\Models\TodoDependencies;
use App\Models\UserEvent;

use App\Http\Resources\SchedulerResource;
use App\Http\Resources\SchedulerAssignmentResource;

class SchedulerController extends Controller
{
    public function index(){
    	$events = Todo::all();
    	$user_events = UserEvent::all();
    	$events = $events->merge($user_events);
    	return SchedulerResource::collection($events);
    }
    public function assignments(){
    	$events = Todo::all();
    	return SchedulerAssignmentResource::collection($events);    
    }
    public function update(Request $request, $id){
    	return true;
    }
}	
