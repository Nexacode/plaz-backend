<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

use App\Http\Resources\TodoResource;

class UserTodoMilestoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $User = Auth::user();
        $User = json_decode($User, true);
        $Roles = $User['token']['realm_access']['roles'];
        $UserId = $User['token']['sub'];      
    
        return [
            'id' => $this->id,
            'name' => $this->milestone,
            //'subcategorie' => Category::where('in_category_id',$this->id)->get(),
            'todos' => TodoResource::collection($this->todo()->where('user_id',$UserId)->where('status',0)->get()),
            //'todos2' => $this->todo()->where('user_id',$UserId)->where('status',0)->get(),
            'category_id' => $this->category_id
        ];
    }
}
