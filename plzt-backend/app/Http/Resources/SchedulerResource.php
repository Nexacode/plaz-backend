<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GanttSegmentResource;
use App\Models\TodoSegment;

class SchedulerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'startDate' => $this->start,
            'endDate' => $this->end,
            'duration' => $this->duration,  
            'durationUnit' => $this->duration_unit,           
            'name' => $this->todo,
            'effort' => $this->effort,
            'eventColor' => $this->color,
            'segments' => TodoSegment::where('todo_id', $this->id)->count() > 0 ? GanttSegmentResource::collection(TodoSegment::where('todo_id', $this->id)->get()) : '',
        ];
    }
}