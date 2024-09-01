<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TodoEvent;
use App\Models\User;
use App\Models\Todo;
use App\Models\Category;
use App\Models\Project;

use Carbon\Carbon;
use DB;

class EventController extends Controller
{
    public function update(Request $request, $id)
    {
    	$data = $request->all();
    	
    	$start = Carbon::parse($request->start);
    	$data['start'] = $start->format('Y-m-d') . "T" . $start->format('H:i');
    	
    	$end = Carbon::parse($request->end);
    	$data['end'] = $end->format('Y-m-d') . "T" . $end->format('H:i');
    	
    	$data['start_date'] = $start->format('Y-m-d H:i');
    	$data['end_date'] = $end->format('Y-m-d H:i');
    	
    	$data['minutes'] = Carbon::parse($data['start_date'])->diff(Carbon::parse($data['end_date']))->format('%I');
    	$data['hours'] = Carbon::parse($data['start_date'])->diff(Carbon::parse($data['end_date']))->format('%h');
    	
    	$Event = TodoEvent::find($id);
    	$Event->update($data);
    	$Events = TodoEvent::where('todo_id',$Event->todo_id)->get();
    	
    	$latestTodo = [];
    	
    	$i = 0;
    	foreach($Events as $item){
    		$latestTodo[$i] = $item['end_date'];
    		$i++;
    	}
    	
    	if(count($latestTodo)>0){
    		$latestTodo = max(array_map('strtotime', $latestTodo));
    		$data['date'] = date('Y-m-j H:i:s', $latestTodo);
    	}
    	
    	
    	
    	$events = TodoEvent::where('todo_id',$Event->todo_id)->get();
    	
    	$latestTodo = [];
    	$firstTodo = [];
    	
    	$i = 0;
    	foreach($events as $item){
    		$firstTodo[$i] = $item['start_date'];
    		$latestTodo[$i] = $item['end_date'];
    		$i++;
    	}
    	
    	$category = Category::find(Todo::find($Event->todo_id)->category_id);
    	$project = Project::find($category->project_id);
    	
    	if(count($latestTodo)>0){
    		$latestTodo = max(array_map('strtotime', $latestTodo));
    		$data['date'] = date('Y-m-j H:i:s', $latestTodo);
    		$latestTodoEvent = Carbon::createFromTimestamp($latestTodo)->format('Y-m-d') . 'T' . date('H:i', $latestTodo);
    		$latestTodo = date('Y-m-j H:i:s', $latestTodo);
    		$category->update(["end_date" => $data['date'], "end" => $latestTodoEvent, "color" => $project->color, 'color_text' => $project->color_text,'color_border' => $project->color_border]);
    		$l = $latestTodoEvent;
    	}
    	
    	if(count($firstTodo)>0){
    		$firstTodo = min(array_map('strtotime', $firstTodo));
    		$firstTodoEvent = Carbon::createFromTimestamp($firstTodo)->format('Y-m-d') . 'T' . date('H:i', $firstTodo);
    		$firstTodo = date('Y-m-j H:i:s', $firstTodo);
    		$category->update(["start_date" => $firstTodo, "start" => $firstTodoEvent, "color" => $project->color, 'color_text' => $project->color_text,'color_border' => $project->color_border]);
    		$f = $firstTodoEvent;
    	}
		
		Todo::find($Event->todo_id)->update(['start'=>$firstTodo,'end'=>$latestTodo,'constraint_date'=>$firstTodo]);
		$project->update(['start'=>$firstTodo,'end'=>$latestTodo,'constraint_date'=>$firstTodo]);
    	
    	return response()->json([
    		"events" => $project,
    		"first" => $firstTodo,
    		"last" => $latestTodo,
    	],201);
    }
    
    public function changeEvents(Request $request)
    {
    	$Event = TodoEvent::find($request->todo_id);
    	
    	$category = Category::find(Todo::find($Event->todo_id)->category_id);
    	$events = TodoEvent::where('category_id',Todo::find($Event->todo_id)->category_id)->get();
    	$project = Project::find($category->project_id);

    	$latestTodo = [];
    	$firstTodo = [];
    	
    	$i = 0;
    	foreach($events as $item){
    		$firstTodo[$i] = $item['start_date'];
    		$latestTodo[$i] = $item['end_date'];
    		$i++;
    	}    
    	
    	if(count($latestTodo)>0){
    		$latestTodo = max(array_map('strtotime', $latestTodo));
    		$data['date'] = date('Y-m-j H:i:s', $latestTodo);
    		$latestTodoEvent = Carbon::createFromTimestamp($latestTodo)->format('Y-m-d') . 'T' . date('H:i', $latestTodo);
    		$latestTodo = date('Y-m-j H:i:s', $latestTodo);
    		$category->update(["end_date" => $data['date'], "end" => $latestTodoEvent, "color" => $project->color, 'color_text' => $project->color_text,'color_border' => $project->color_border]);
    		$l = $latestTodoEvent;
    	}
    	
    	if(count($firstTodo)>0){
    		$firstTodo = min(array_map('strtotime', $firstTodo));
    		$firstTodoEvent = Carbon::createFromTimestamp($firstTodo)->format('Y-m-d') . 'T' . date('H:i', $firstTodo);
    		$firstTodo = date('Y-m-j H:i:s', $firstTodo);
    		$category->update(["start_date" => $firstTodo, "start" => $firstTodoEvent, "color" => $project->color, 'color_text' => $project->color_text,'color_border' => $project->color_border]);
    		$f = $firstTodoEvent;
    	}    		
    	
    	$changeEvents = TodoEvent::where('id','!=',$request->todo_id)->where('project_id',$Event->project_id)->where('start','>',$Event->end)->get();
    	//$changeEvents = count($changeEvents);
    	
    	return response()->json("change" . $changeEvents,201);
    }
    
    public function availebaleUserHours(Request $request)
    {
    	$userinfo = User::where('keycloak_id',$request->user_id)->with('times', function ($query) use ($request) {
    		return $query->where('day', '=', $request->dayofweek);
		})->get();
		

    	
		//$userinfo = User::with('times')->whereHas('times', function ($query) use ($request) {
    	//	return $query->where('day', '=', $request->dayofweek);
		//})->get();  
		
		//$dateinfo = TodoEvent::whereDate('start_date', $request->date)->get(); 
			
		//$userinfo[0]['times'][0]['hours']
		
		//$hours = Carbon::parse($dateinfo[0]['start_date'])->diff(Carbon::parse($dateinfo[0]['end_date']))->format('%H:%I')." Minutes";
    	
    	return response()->json($userinfo,201);
    }
    
    public function checkUserHours(Request $request)
    {
    	$TodoEvents = TodoEvent::where('end_date','>',date("Y-m-d"))
    					->groupBy(DB::raw("DATE_FORMAT(start_date, '%d-%m-%Y')"))
    					->get();
    	$UserTimes = User::with('times')->whereHas('times')->get();
    	return response()->json($TodoEvents,201);
    }
    
    public function resetTodoEvents(Request $request)
    {
    	$future = TodoEvent::where('start_date','>',date($request->start_date))->where('user_id',$request->user_id)->where('todo_id','!=',$request->todo_id)->orderBy('start_date')->limit(1)->get();
    	
    	if(count($future) >= 1){
    	
    		$getnext = [];

    		$getnext[0]['old_start'] = $future[0]['start_date'];
    		$getnext[0]['div'] = Carbon::parse($request->start_date)->diffInHours(Carbon::parse($future[0]['start_date']));
    		$getnext[0]['new_start'] = Carbon::parse($future[0]['start_date'])->subHours($getnext[0]['div']);
    	
    		$i = 0;
    	
    		$allfuture = TodoEvent::where('start_date','>',date($request->start_date))->where('user_id',$request->user_id)->where('todo_id','!=',$request->todo_id)->orderBy('start_date')->get();
    	
    		$newdate = [];
    	
    		foreach($allfuture as $item){
    			$newdate[$i]['old_start'] = $item['start_date'];
    			$newdate[$i]['div'] = Carbon::parse($request->start_date)->diffInHours(Carbon::parse($item['start_date']));
    			$newdate[$i]['new_start'] = Carbon::parse($item['start_date'])->subHours($getnext[0]['div']);
    			$newdate[$i]['todo_id'] = $item['todo_id'];
    			$i++;
    		}
    	
    		return response()->json($newdate,201);
    	
    	}    	
    }
    
    public function destroy($event)
    {
    	TodoEvent::destroy($event);
    	return response()->json(true,201);
    	
    }    
}
