<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Models\Milestone;
use App\Models\Todo;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\MilestoneResource;

class CategoryResource extends JsonResource
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
            'subcategory' => SubCategoryResource::collection(Category::where('in_category_id',$this->id)->where('done',0)->get()),
            'milestones' => MilestoneResource::collection(Milestone::where('category_id',$this->id)->get()),
            'descriptions' => $this->description()->count(),
            'time' => Todo::where('main_category_id',$this->id)->where('status',0)->sum('estimated_time'),
            'undone' => Todo::where('main_category_id',$this->id)->where('status',0)->count(),
            'has_todo' => Todo::where('main_category_id',$this->id)->exists(),
            'last_todo' => Todo::where('main_category_id',$this->id)->orderBy('date','DESC')->first()
        ];
    }
}
