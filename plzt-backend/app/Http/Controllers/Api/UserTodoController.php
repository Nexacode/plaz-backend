<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Todo;
use Auth;

use App\Http\Resources\UserTodoResource;

class UserTodoController extends Controller
{
    public function index()
    {
        $User = Auth::user();
        $User = json_decode($User, true);
        $Roles = $User['token']['realm_access']['roles'];
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $abfrage = Project::whereHas('milestones',function($query) use ($UserId){
        	$query->whereHas('todo', function ($query) use ($UserId){
        		$query->where('user_id',$UserId);
        	});
        })->with('milestones.todo',function($query) use ($UserId){
        		$query->where('user_id',$UserId)
        			  ->where('status',0);
        	})
        ->get();
            
        return response()->json($abfrage, 201);
    }
    
    public function mytodo()
    {

        $User = Auth::user();
        $User = json_decode($User, true);
        $Roles = $User['token']['realm_access']['roles'];
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $abfrage = Project::whereHas('milestones',function($query) use ($UserId){
        	$query->whereHas('todo', function ($query) use ($UserId){
        		$query->where('user_id',$UserId);
        	});
        })->with('milestones.todo',function($query) use ($UserId){
        		$query->where('user_id',$UserId)
        			  ->where('status',0);
        	})
        ->get();
            
    	return UserTodoResource::collection($abfrage);
    }
    
    public function myopentodo()
    {

        $User = Auth::user();
        $User = json_decode($User, true);
        $Roles = $User['token']['realm_access']['roles'];
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $abfrage = Project::whereHas('milestones',function($query) use ($UserId){
        	$query->whereHas('todo', function ($query) use ($UserId){
        		$query->where('user_id',$UserId);
        	});
        })->with('milestones.todo',function($query) use ($UserId){
        		$query->where('user_id',$UserId)
        			  ->where('status',0);
        	})
        ->get();
        
        $todos = Todo::where('user_id',$UserId)->where('todo_status_id','!=',11)->withSum('work','time')->get();
            
    	return response()->json($todos, 201);
    }
    
    public function myTodoWithoutAssesment()
    {

        $User = Auth::user();
        $User = json_decode($User, true);
        $Roles = $User['token']['realm_access']['roles'];
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $abfrage = Project::whereHas('milestones',function($query) use ($UserId){
        	$query->whereHas('todo', function ($query) use ($UserId){
        		$query->where('user_id',$UserId);
        	});
        })->with('milestones.todo',function($query) use ($UserId){
        		$query->where('user_id',$UserId)
        			  ->where('status',0);
        	})
        ->get();
        
        $todos = Todo::where('user_id',$UserId)->where('status',1)->where('todo_status_id','!=',11)->withSum('work','time')->get();
            
    	return response()->json($todos, 201);
    }
    
    public function userTodo(Request $request)
    {

        $User = Auth::user();
        $User = json_decode($User, true);
        $Roles = $User['token']['realm_access']['roles'];
        $UserId = $request->user_id;
        
        $UserName = $User['token']['name'];
        
        $abfrage = Project::whereHas('milestones',function($query) use ($UserId){
        	$query->whereHas('todo', function ($query) use ($UserId){
        		$query->where('user_id',$UserId)
        			  ->where('status',0);
        	});
        })->with('milestones.todo',function($query) use ($UserId){
        		$query->where('user_id',$UserId)
        			  ->where('status',0);
        	})
        ->get();
        
        $Todo = Todo::where('user_id',$request->user_id)->where('status',0)->withSum('work','time')->get();
       
          
        return response()->json($Todo, 201);  
    	//return UserTodoResource::collection($abfrage);  	
    }
}
