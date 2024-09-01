<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GanttSegmentResource extends JsonResource
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
            'startDate' => $this->start,
            'endDate' => $this->end,    
            'duration' => $this->duration,
            'durationUnit' => $this->duration_unit
        ];
    }
}
