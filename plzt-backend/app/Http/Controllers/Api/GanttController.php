<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\GanttResource;
use App\Models\Project;
use App\Models\Todo;
use App\Models\TodoDependencies;
use App\Models\TodoResource;

use Carbon\Carbon;
use DB;
class GanttController extends Controller
{



    public function index()
    {
    	$projects = Project::orderByRaw('ISNULL(priority), priority ASC')->orderBy('parent_index')->where('status',1)->where('in_calendar',1)->where('sub_project',false)->get();
    	return GanttResource::collection($projects);
    }
    
    public function update(Request $request,$id)
    {
    	//check why we have move errors
		$move = false;
		$newbiggerold = false;
		$newsmallerold = false;
		
		$update = "";    
    
if ($request->what === "task") {
    $task = Todo::find($id);
    $oldPosition = $task->parent_index;
    
	$newPosition = $request->parentIndex; // Die neue Position, z.B., 4
 // Die alte Position, die Sie abgerufen haben
 
	$decrement = [];
	$increment = [];

if ($newPosition != $oldPosition) {
    $todoToMove = Todo::where('parent_index', $oldPosition)->where('project_id',$task->project_id)->first();

    if ($newPosition > $oldPosition) {
    	$newbiggerold = true;
		Todo::whereBetween('parent_index', [$oldPosition + 1, $newPosition])
        	->where('id', '!=', $task->id)
        	->update(['parent_index' => DB::raw('parent_index - 1'),'ordered_parent_index' => DB::raw('ordered_parent_index - 1')]);
    } 
    
    if ($newPosition < $oldPosition) {
    	$newsmallerold = true;
    	
    	Todo::whereBetween('parent_index', [$newPosition, $oldPosition - 1])
        	->where('id', '!=', $task->id)
        	->update(['parent_index' => DB::raw('parent_index + 1'),'ordered_parent_index' => DB::raw('ordered_parent_index + 1')]);
    }

	$move = true;
} 
    
    
    if ($task) {
        $task->update([
            'duration' => $request->duration,
            'duration_unit' => $request->durationUnit,
            'start' => Carbon::parse($request->startDate)->setTimezone('Europe/Berlin')->format('Y-m-d H:i'),
            'end' => Carbon::parse($request->endDate)->setTimezone('Europe/Berlin')->format('Y-m-d H:i'),
            'note' => $request->note,
            'percent_done' => $request->percentDone,
            'effort' => $request->effort,
            'effort_driven' => $request->effortDriven,
            'effort_unit' => $request->effortUnit,
            'constraint_date' => Carbon::parse($request->constraintDate),
            'constraint_type' => $request->constraintType,
            'parent_index' => $request->parentIndex,
            'parent_id' => $request->parentId,
            'ordered_parent_index' => $request->parentIndex,
            'todo' => $request->name,
            'color' => $request->eventColor
        ]);
        
     
        $modifiedSegments = [];
        $test = [];
        

        
		$modifiedSegments = collect($request->segments)->map(function ($segment) {
    		return [
        		'start' => Carbon::parse($segment['startDate'])->format('Y-m-d H:i') ?? null,
        		'end' => Carbon::parse($segment['endDate'])->format('Y-m-d H:i') ?? null,
        		'duration' => Carbon::parse(Carbon::parse($segment['startDate'])->format('Y-m-d H:i'))->diffInHours(Carbon::parse($segment['endDate'])->format('Y-m-d H:i')),
        		'duration_unit' => "h"
    		];
		})->all();
		
		if(!empty($request->segments)){
    		$task->segments()->delete();
    		$task->segments()->createMany($modifiedSegments);
		} else {
			$task->segments()->delete();
		}
    }
}

if ($request->what === "project") {
    $projectId = ltrim($id, 'p');
    $project = Project::find($projectId);
    
    $oldPosition = $project->parent_index;
    
    //
	$newPosition = $request->parentIndex; // Die neue Position, z.B., 4
 	// Die alte Position, die Sie abgerufen haben
 
	$decrement = [];
	$increment = [];

	if ($newPosition != $oldPosition) {
    	//$todoToMove = Todo::where('parent_index', $oldPosition)->where('project_id',$task->project_id)->first();

    if ($newPosition > $oldPosition) {        
        Project::whereBetween('parent_index', [$oldPosition + 1, $newPosition])
        	->where('id', '!=', $project->id)
        	->update(['parent_index' => DB::raw('parent_index - 1')]);
    } 
    if ($newPosition < $oldPosition) {
    	Project::whereBetween('parent_index', [$newPosition, $oldPosition - 1])
        	->where('id', '!=', $project->id)
        	->update(['parent_index' => DB::raw('parent_index + 1')]);
    }


}
//     

       Project::find($projectId)->update([
       		'name' => $request->name,
            'duration' => $request->duration,
            'duration_unit' => $request->durationUnit,
            'cls' => $request->cls,
            'start' => Carbon::parse($request->startDate),
            'end' => Carbon::parse($request->endDate),
            'direction' => $request->direction,
            'manually_scheduled' => $request->manuallyScheduled,
            'constraint_type' => $request->constraintType,
            'constraint_date' => Carbon::parse($request->constraintDate),
            'effort' => $request->effort,
            'effort_unit' => $request->effortUnit,
            'effort_driven' => $request->effortDriven,
            'percent_done' => $request->percentDone,
            'expanded' => $request->expanded,
            'note' => $request->note,
            'parent_index' => $request->parentIndex,
            'project_id' => $projectId,
            'scheduling_mode' => $request->scheduling_mode,
            	'gantt_status' => $request->status
        	]);
	}	
	
    	$ret = [
    		"start" => Carbon::parse($request->startDate)->format('Y-m-d H:i'),
    		"end" => Carbon::parse($request->endDate)->format('Y-m-d H:i'),
    		"request" => Carbon::parse($request->constraintDate),
    		"what" => $request->what,
    		"project" => Carbon::parse($request->constraintDate),
    		"parent_index" => $request->parentIndex,
    		"ordered_parent_index" => $request->orderedParentIndex,
    		"name" => $request->name,
    		"segments" => $request->segments ? $request->segments : 'none',
    		"decrement" => $decrement,
    		"increment" => $increment,
    		"move" => $move,
    		"newbiggerold" => $newbiggerold,
    		"newsmallerold" => $newsmallerold
    	];
    	return response()->json($ret,201);
    }
    
    public function dependencies(Request $request)
    {
    	TodoDependencies::create($request->all());
    	return response()->json($request,201);
    }
    
    public function getDependencies()
    {
    	return response()->json(TodoDependencies::all(),201);    
    }
    
    public function delDependencies($id)
    {
    	return response()->json(TodoDependencies::find($id)->delete(),201);    
    }    
    
    public function resources(Request $request)
    {
    	$data = [];
    	$data['user_id'] = $request->id;
    	$data['user_name'] = $request->name;
    	$data['todo_id'] = $request->todo_id;
    	TodoResource::create($data);
    	return response()->json($request,201);
    }
    
}
