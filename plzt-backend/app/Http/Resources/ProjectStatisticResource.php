<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

use App\Models\Project;
use App\Models\Milestone;
use App\Models\Todo;
use App\Models\Work;
use App\Http\Resources\SubProjectResource;
use App\Http\Resources\SubProjectStatisticResource;

class ProjectStatisticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {    	
    	$projects = Project::select('id')->where('project_id',$this->id)->where('status',1)->get()->map(function ($project) {
    			return $project->id;
		});
    	
        return [
            'id' => $this->id,
            'name' => $this->name,
            'time' => $this->works()->whereMonth('date',Carbon::now()->subMonth()->month)->whereYear('date',Carbon::now()->subMonth()->year)->sum('time'),
            'sub_projects' => SubProjectStatisticResource::collection(Project::where('project_id',$this->id)->where('status',1)->get()),
 //           'sub_projects2' => Project::select('id')->where('project_id',$this->id)->where('status',1)->get()->map(function ($project) {
 //   			return $project->id;
//			}),
            'sum' => $this->works()->whereMonth('date',Carbon::now()->subMonth()->month)->whereYear('date',Carbon::now()->subMonth()->year)->sum('time') + Work::whereIn('project_id', $projects)->whereMonth('date',Carbon::now()->subMonth()->month)->whereYear('date',Carbon::now()->subMonth()->year)->sum('time'),
            
        ];
    }
}