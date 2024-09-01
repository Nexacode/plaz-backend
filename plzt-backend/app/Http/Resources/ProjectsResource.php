<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\Todo;
use App\Http\Resources\SubProjectResource;

class ProjectsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            'time' => $this->milestones()->withSum('todo','estimated_time')->get(),

            //'employee' => $this->milestones()->with('todo')->withCount('employee')->get(),

            'status' => $this->status,
            'deadline' => $this->deadline,
            'done' => $this->milestones()->with('donetodos')->exists(),
            'project_time' => $this->todo()->sum('estimated_time'),
            'todo_done' => $this->todoopen()->exists(),
            'todo_open' => $this->tododone()->sum('estimated_time'),
            'todo_count' => $this->alltodo()->count(),
            'todo' => $this->todo()->with('categories')->get(),
            "cat" => "leer",
            'employee' => $this->employee->groupBy('employee')->count(),
            //'employee' => $this->milestones()->withCount('employee')->groupBy('employee')->get(),
            'planing_status' => $this->planing_status,
            'has_milestone' => $this->milestones()->exists(),
            'has_category' => $this->categories()->exists(),
            'time' => $this->worktime(),
            'priority' => $this->priority,
            'finished' => $this->lasttodo(),
            'is_sub_project' => $this->sub_project,
			'sub_projects' => SubProjectResource::collection(Project::where('project_id',$this->id)->where('status',1)->get()),
            'project_id' => $this->project_id,
            'color' => $this->color,
            'color_border' => $this->color_border,
            'description' => $this->description,
            'in_calendar' => $this->in_calendar
        ];
    }
}
