<?php

namespace App\Http\Resources;
use App\Http\Resources\GanttSegmentResource;
use App\Models\TodoSegment;

use Illuminate\Http\Resources\Json\JsonResource;

class GanttChildResource extends JsonResource
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
            'name' => $this->todo,
            'startDate' => $this->start,
            'endDate' => $this->end,
            'constraintDate' => $this->constraint_date,
            'constraintType' => $this->constraint_type,
            'duration' => $this->duration,
            'durationUnit' => $this->duration_unit,
            'effort' => $this->effort,
            'effortUnit' => $this->effort_unit,
            'percentDone' => $this->percent_done,
            'segments' => TodoSegment::where('todo_id', $this->id)->count() > 0 ? GanttSegmentResource::collection(TodoSegment::where('todo_id', $this->id)->get()) : '',
            'parentIndex' => $this->parent_index,
            'orderedParentIndex' => $this->ordered_parent_index,
            'eventColor' => $this->color
        ];
    }
}
