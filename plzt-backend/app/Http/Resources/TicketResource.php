<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'status' => $this->status,
            'from_name' => $this->from_name,
            'type_id' => $this->type_id,
			'subject' => $this->subject,
            'priority' => $this->priority,
            'external_project' => $this->external_project,
			'work_sum' => $this->workSum()->sum('time'),
			'works' => $this->works()->get(),
			'created_at' => $this->created_at,
        ];
    }
}
