<?php

namespace App\Http\Resources;
use App\Http\Resources\GanttChildResource;
use App\Http\Resources\GanttProjectChildResource;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Project;
use App\Models\Todo;
use App\Models\Categorie;

class GanttResource extends JsonResource
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
            'id' => "p" . $this->id,
            'startDate' => $this->start,
            'endDate' => $this->end,            
            'name' => $this->name,
            'expanded' => $this->expanded,
			'children' => GanttProjectChildResource::collection(Project::where('project_id', $this->id)->where('sub_project', true)->where('status',1)->where('in_calendar',true)->orderBy('parent_index')->get())
    								->concat(GanttChildResource::collection(Todo::where('project_id', $this->id)->where('in_calendar',true)->orderBy('parent_index')->get())),
            'parentId' => "p" . $this->project_id,
            'parentIndex' => $this->parent_index,
            'constraintDate' => $this->constraint_date,
            'constraintType' => $this->constraint_type,
            'eventColor' => $this->color,
            'effort' => $this->effort,
            'effort_unit' => $this->effort_unit,
            'percentDone' => $this->percent_done,
            'manuallyScheduled' => $this->manually_scheduled
        ];
    }
}
