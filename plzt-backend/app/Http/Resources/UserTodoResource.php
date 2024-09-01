<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Auth;
use App\Http\Resources\UserTodoMilestoneResource;
use App\Models\Milestone;

class UserTodoResource extends JsonResource
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
            'name' => $this->name,

            'time' => $this->milestones()->withSum('todo','estimated_time')->get(),

            //'employee' => $this->milestones()->with('todo')->withCount('employee')->get(),

            'status' => $this->status,
            'deadline' => $this->deadline,
            'done' => $this->milestones()->with('donetodos')->exists(),
            'project_time' => $this->todo()->sum('estimated_time'),
            'todo_done' => $this->todoopen()->exists(),
            'todo_open' => $this->tododone()->sum('estimated_time'),
            'todo_count' => $this->alltodo()->count(),
            //'todo' => $this->todoopen()->get(),
            //'todo2' => $this->todo()->where('user_id',$UserId)->where('status',0)->count(),
            'employee' => $this->employee->groupBy('employee')->count(),
            //'employee' => $this->milestones()->withCount('employee')->groupBy('employee')->get(),
            'planing_status' => $this->planing_status,
            'has_milestone' => $this->milestones()->exists(),
            'has_category' => $this->categories()->exists(),
            'user_name' => $this->user_name,
            'milestones' => UserTodoMilestoneResource::collection(
            	Milestone::where('project_id',$this->id)->
            	    whereHas('todo',function($query) use ($UserId){
                        $query->where('status',0)->where('user_id',$UserId);
        	        })
                ->get()),
        ];
    }
}
