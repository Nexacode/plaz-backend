<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Project;
use App\Http\Resources\CalendarSubProjectEventsResource;

class CalendarProjectResourceResource extends JsonResource
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
            'title' => $this->name,
            'children' => CalendarSubProjectEventsResource::collection(Project::where('project_id',$this->id)->get())
        ];
    }
}
