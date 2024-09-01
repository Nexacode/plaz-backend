<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Work;
use App\Http\Resources\ProjectStatisticResource;

use Carbon\Carbon;
use DB;

class StatisticController extends Controller
{
    public function projectStatistic(){
    
    	$today = Carbon::now();
    
		$project = Project::
						where('status',1)->
						where('sub_project',0)->
						whereHas('works', function ($q) use ($today) {
							return $q->whereMonth('date',$today->subMonth()->month)->whereYear('date',$today->subMonth()->year);
						})->get();
						
    	return ProjectStatisticResource::collection($project);
    
    }
    
    public function userStatistic(){
    
    }
    
    public function projectStatisticSelectet($project){
    	$work = Work::select(DB::raw('MONTH(date) month'),
    DB::raw('SUM(time) as total_time'))->whereYear('date',Carbon::now()->subMonth()->year)->where('project_id',76)->groupBy('month')->get();
    	return response()->json($work,201);	    	
    }
    
    public function projectStatisticUser(){
    	$work = Work::select(DB::raw('MONTH(date) month'),DB::raw('SUM(time) as total_time'),'user_name')
    				->whereYear('date',Carbon::now()->subMonth()->year)
    				->where('project_id',76)
    				->groupBy('user_name','month')->get();
    				
    	return response()->json($work,201);	    	
    }
    
    public function projectStatisticDaily(){
    
    	$statistic = [];
    	$statistic['last_month'] = Work::select(DB::raw('SUM(time) as total_time'),DB::raw("DATE_FORMAT(date, '%d.%m.%Y') as day"))
    				->whereYear('date',Carbon::now()->subMonth()->year)
    				->whereMonth('date',Carbon::now()->subMonth()->month)
    				->where('project_id',76)
    				->groupBy('date')->get();
    	$statistic['this_month'] = Work::select(DB::raw('SUM(time) as total_time'),DB::raw("DATE_FORMAT(date, '%d.%m.%Y') as day"))
    				->whereYear('date',Carbon::now()->year)
    				->whereMonth('date',Carbon::now()->month)
    				->where('project_id',76)
    				->groupBy('date')->get();
    	$statistic['this_month_sum'] = Work::
    				whereYear('date',Carbon::now()->year)
    				->whereMonth('date',Carbon::now()->month)
    				->where('project_id',76)->sum('time');
    	$statistic['last_month_sum'] = Work::
    				whereYear('date',Carbon::now()->subMonth()->year)
    				->whereMonth('date',Carbon::now()->subMonth()->month)
    				->where('project_id',76)->sum('time');
    				
    	return response()->json($statistic,201);	    	
    }
    
}
