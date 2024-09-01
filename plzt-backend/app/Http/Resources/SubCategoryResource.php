<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Models\Milestone;
use App\Models\Todo;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\MilestoneResource;
class SubCategoryResource extends JsonResource
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
            'name' => $this->name,
            //'subcategorie' => Category::where('in_category_id',$this->id)->get(),
            'subcategory' => SubCategoryResource::collection(Category::where('in_category_id',$this->id)->get()),
            'milestones' => MilestoneResource::collection(Milestone::where('category_id',$this->id)->get()),
            'time' => Todo::where('category_id',$this->id)->where('status',0)->sum('estimated_time'),
            'last_todo' => Todo::where('category_id',$this->id)->orderBy('date','DESC')->first(),
            'undone' => Todo::where('category_id',$this->id)->where('status',0)->count(),
            'has_todo' => Todo::where('category_id',$this->id)->exists(),
        ];
    }
}
