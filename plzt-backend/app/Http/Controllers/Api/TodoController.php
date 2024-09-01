<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Todo;
use App\Models\TodoEvent;
use App\Models\Project;
use App\Models\Category;
use App\Http\Resources\CalendarEventsResource;

use Carbon\Carbon;
use Auth;

class TodoController extends Controller
{

    public function index()
    {
    	return CalendarEventsResource::collection(TodoEvent::all());
    }

    public function store(Request $request)
    {
    	$Project = Project::find($request->project_id);
    	
    	$childdata = array();
    	
    	foreach($request->events as $events){
    		$childdata[] = array(
    			'project_id' => $events['project_id'],
    			'start' => $events['date'] . "T" . $events['start'],
    			'end' => $events['date'] . "T" . $events['end'],
    			'constrained_date' => $events['date'] . " " . $events['start'],
    			'start_date' => $events['date'] . " " . $events['start'],
    			'end_date' => $events['date'] . " " . $events['end'],
    			'user_id' => $request->user_id,
    			'color' => $Project->color,
    			'color_text' => $Project->color_text,
    			'color_border' => $Project->color_border,
    			'hours' => $events['hours'],
    			'minutes' => $events['minutes'],
    		);
    	}
    	
    	$request->merge(['duration' => Carbon::parse($request->start)->diffInDays($request->end),'duration_unit' => "days", 'parent_index' => Todo::where('project_id',$request->project_id)->count(),'ordered_parent_index' => Todo::where('project_id',$request->project_id)->count()]);
    	$todo = Todo::create($request->except(['events']));
    	$todo->events()->createMany($childdata);
    	$todo->histories()->create(['todo_status_id' => 1]);
    	
    	return response()->json($childdata,201);
    }

    public function show($id)
    {
    	$Todo = Todo::with('events')->with('milestone')->find($id);
    	//->with('events')->with('milestone')
    	$Project = Project::find($Todo->project_id);
    	
    	if($Project->sub_project == 1){
    		$Project = Project::find($Project->project_id);
    	}
    	
    	$Todo['project'] = $Project->name;
    	
        return response()->json([$Todo], 201);
    }

    public function update(Request $request, $id)
    {
    
    	$user = Auth::user();
    	$user_id = $user->token->sub;
    	$user_name = $user->token->name;
    
    	$project = Project::find($request->project_id);
    	
    	$childdata = array();
    	
    	foreach($request->events as $events){
    		$childdata[] = array(
    			'project_id' => $events['project_id'],
    			'category_id' => $events['category_id'],
    			'start' => $events['date'] . "T" . $events['start'],
    			'end' => $events['date'] . "T" . $events['end'],
    			'constrained_date' => $events['date'] . " " . $events['start'],
    			'start_date' => $events['date'] . " " . $events['start'],
    			'end_date' => $events['date'] . " " . $events['end'],
    			'user_id' => $request->user_id,
    			'color' => $project->color,
    			'color_text' => $project->color_text,
    			'color_border' => $project->color_border,    			
    			'hours' => $events['hours'],
    			'minutes' => $events['minutes'],    			
    		);
    	} 	
    	
    	$data = $request->except(['events','start','end','start_time','end_time']);
    	$data['constraint_date'] = $request->start . ' ' . $request->start_time;
    	$data['constraint_type'] = 'startnoearlierthan';
    	$data['color'] = $project->color;
    	
    	if($request->start && $request->end){
    		$data['start'] = $request->start . ' ' . $request->start_time;
    		$data['end'] = $request->end . ' ' . $request->end_time;
    		
    		if($request->start_time && $request->end_time != null){
    			$data['duration'] = Carbon::parse($request->start . $request->start_time)->diffInHours($request->end . $request->end_time);
    			$data['duration_unit'] = "hours";  		
    		}
    	}
    	
    	$latestTodo = [];
    	$firstTodo = [];
    	$firstTodoEvent = NULL;
    	$latestTodoEvent = NULL;
    	
    	$i = 0;
    	foreach($childdata as $item){
    		$firstTodo[$i] = $item['start_date'];
    		$latestTodo[$i] = $item['end_date'];
    		$i++;
    	}
    	
    	Todo::find($id)->update($data);
    	
    	$todo = Todo::find($id);
    	$todo->events()->delete();
    	$todo->events()->createMany($childdata);
    	
    	$sameStatus = $todo->histories()->where('todo_status_id',$request->todo_status_id)->exists();
    	
    	if(!$sameStatus){
    		$todo->histories()->create(['todo_status_id' => $request->todo_status_id,'user_name' => $user_name,'user_id' => $user_id]);
    	}
    	
    	$category = Category::find($todo->category_id);
    	
    	if(count($latestTodo)>0){
    		$latestTodo = max(array_map('strtotime', $latestTodo));
    		$data['date'] = date('Y-m-j H:i:s', $latestTodo);
    		$latestTodoEvent = Carbon::createFromTimestamp($latestTodo)->format('Y-m-d') . 'T' . date('H:i', $latestTodo);
    		$latestTodo = date('Y-m-j H:i:s', $latestTodo);
    		$category->update(["end_date" => $data['date'], "end" => $latestTodoEvent, 'color' => $project->color, 'color_text' => $project->color_text,'color_border' => $project->color_border]);
    	}
    	
    	if(count($firstTodo)>0){
    		$firstTodo = min(array_map('strtotime', $firstTodo));
    		$firstTodoEvent = Carbon::createFromTimestamp($firstTodo)->format('Y-m-d') . 'T' . date('H:i', $firstTodo);
    		$firstTodo = date('Y-m-j H:i:s', $firstTodo);
    		$category->update(["start_date" => $firstTodo, "start" => $firstTodoEvent, 'color' => $project->color, 'color_text' => $project->color_text,'color_border' => $project->color_border]);
    	}
    	
        return response()->json($request, 201);
    }
    
    public function updateevent(Request $request, $id)
    {
    	return response()->json(true, 201);
    }    

    public function destroy($id)
    {
        //
    }
    
    public function openTodo($project)
    {
    	return response()->json([Todo::whereIn('todo_status_id', [1,2,3,4,5,6,7,8,10,12,13])->where('project_id',$project)->where('status',0)->whereIn('checking_status',[0,3])->with('workSum')->get()], 201);
    }
}
