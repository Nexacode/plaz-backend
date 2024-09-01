<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Todo;
use App\Http\Resources\ProjectsResource;
use App\Models\Milestone;
use App\Models\Category;

use App\Http\Resources\CategoryResource;

use PDF;

class ProjectController extends Controller
{
    public function index()
    {
    	$Projects = Project::orderByRaw('ISNULL(priority), priority ASC')->where('status',1)->get();
    	return ProjectsResource::collection($Projects);
    }
    
    public function store(Request $request)
    {
    	$request->request->add(['status' => 1]);
    	Project::create($request->all());
    	return response()->json(true,201);
    }  
    
    public function update(Request $request,$id)
    {
    	Project::find($id)->update($request->all());
    	return response()->json(true,201);	
    }
    
    public function destroy($project)
    {
    	if(Project::destroy($project)){
    		return response()->json(true,201);
    	};
    }  
    
    public function show($id)
    {
        return response()->json(Milestone::where('project_id',$id)->with('todo')->orderBy('priority')->get(), 201);
    } 
    
    public function getSubProjects($id)
    {
    	$subprojects = Project::where('project_id',$id)->get();
    	return response()->json($subprojects,201);
    }
    
    public function planning($id)
    {
        return response()->json([Project::find($id)], 201);
    } 
    
    public function employeeStatistic($id)
    {
    	$Todo2 = array();
    	$Todo = Todo::where('project_id',$id)->get();
    	$Todo2[] = $Todo->keyBy('user_name');
    	
    	$Todo = Todo::selectRaw('sum(estimated_time) as amount')->groupBy('user_name')->get();
    	$Todo = Todo::where('project_id',$id)->withSum('assessmentSum','time')->withSum('work','time')->get()->groupBy('user_name');
    	//$Todo = collect($Todo);

    	
    	//$attrs = collect($Todo)->groupBy('user_name');

    	return response()->json($Todo, 201);
    }
    
    public function showPDF($project)
    {

			$data = $project;
			$Categories = Category::where('project_id',$project)->get();
			
			view()->share('data', $data);
			$pdf = PDF::loadView('pdf.project', compact('data','Categories'));
			return $pdf->stream();
    }          
    
}
