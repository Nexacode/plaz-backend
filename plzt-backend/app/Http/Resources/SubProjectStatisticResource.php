<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class SubProjectStatisticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	$today = Carbon::now();
    	
        return [
            'id' => $this->id,
            'name' => $this->name,
            'time' => $this->works()->whereMonth('date',$today->subMonth()->month)->whereYear('date',$today->subMonth()->year)->sum('time'),
        ];
    }
}
