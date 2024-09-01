<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventsResource extends JsonResource
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
            'start' => $this->start,
            'end' => $this->end,
            'resourceId' => $this->user_id,
            'title' => $this->todo,
            'color' => $this->color,
            'textColor' => $this->color_text,
            'borderColor' => $this->color_border,
            'user_id' => $this->user_id,
            'title' => $this->todo()->select('todo')->get()[0]->todo  ?? $this->title,
            'todo_id' => $this->todo_id,
            'start_date' => $this->start_date,
            'display' => $this->display
        ];
    }
}
