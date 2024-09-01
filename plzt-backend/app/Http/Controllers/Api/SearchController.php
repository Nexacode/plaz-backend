<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Milestone;

class SearchController extends Controller
{
    public function search(Request $request)
    {
    	$Milestones = Milestone::query();
    	
        if (!empty($request->created_at)) {
            $Milestones->whereDate('created_at', $request->created_at);
        }
        	
        if (!empty($request->priority)) {
            $Milestones->where('priority', $request->priority);
        }  
        
        if (!empty($request->milestone)) {
            $Milestones->where('milestone','LIKE', "%$request->milestone%");
        }    
        
        if (!empty($request->todo)) {
			$Milestones->whereHas('todo', function ($query) use ($request){
				$query->where('todo','LIKE','%'. $request->todo . '%');
			});
        }
        
        if (!empty($request->employee)) {
			$Milestones->whereHas('todo', function ($query) use ($request){
				$query->where('employee',$request->employee);
			});
        }           	
    	
        if (!empty($request->discussed)) {
			$Milestones->whereHas('todo', function ($query) use ($request){
				$query->where('discussed',1);
			});
        } 
        
        if (!empty($request->status)) {
			$Milestones->whereHas('todo', function ($query) use ($request){
				$query->where('status',1);
			});
        }

        if (!empty($request->online_test)) {
			$Milestones->whereHas('todo', function ($query) use ($request){
				$query->whereDate('online_test', $request->online_test);
			});
        } 

        if (!empty($request->online_live)) {
			$Milestones->whereHas('todo', function ($query) use ($request){
				$query->whereDate('online_live', $request->online_live);
			});
        }    	
    	
    	//$Milestones = Milestone::with('todo')->orderBy('priority')->get();
    	return response()->json($Milestones->with('todo')->get(), 201);
    }
}
