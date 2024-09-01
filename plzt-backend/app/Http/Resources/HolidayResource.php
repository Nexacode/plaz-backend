<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
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
            'start' => $this->start,
            'end' => $this->start === $this->end ? $this->end : $this->end . 'T23:59',
            'title' => $this->status == 0 ? $this->contraction . " beantragt" : $this->contraction,
            'color' => $this->color
        ];
    }
}
