<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
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
            'todo' => $this->todo,
            'description' => $this->description,
            'assessment' => $this->assessment()->latest('created_at')->first(),
            'work' => $this->work()->sum('time'),
            'date' => $this->date,
            'user_name' => $this->user_name,
            'project_id' => $this->project_id,
            'active' => $this->active,
            'priority' => $this->priority,
            'checking_status' => $this->checking_status,
            'estimated_time' => $this->estimated_time,
        ];
    }
}
