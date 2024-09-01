<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use App\Models\Project;
use App\Models\User;

use DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		$projects = Project::where('status',1)
						->where('id', '!=' , 4)
						->where('id', '!=' , 76)
						->where('id', '!=' , 27)
						->where('id', '!=' , 36)
						->where('id', '!=' , 44)
						->where('id', '!=' , 63)
						->with(['todo'])->with(['todoopenstatistic' => function($q){
			$q->sum('estimated_time');
		}])->with('parentproject')->withSum('todoopenstatistic','calculated_time')->get();
		//$projects->todo()->where('status',0);
		//dd($projects);
		$projecttime = Todo::with(['project' => function($q){
			$q->where('status',1)
			  ->where('id', '!=' , 4)
			  ->where('id', '!=' , 76)
			  ->where('id', '!=' , 27)
			  ->where('id', '!=' , 36)
			  ->where('id', '!=' , 44)
			  ->where('id', '!=' , 63);
		}])->where('todo_status_id','<',7)->select(DB::raw("SUM(calculated_time) as project_time"))->get();
		
		$calctime = 0;
		foreach($projects as $project){
		$calctime += $project->todoopenstatistic_sum_calculated_time;
		}
		//dd($projects);
		$usertime = User::query()->select(DB::raw("SUM(time_per_week) as user_time"))->get();
		$users = User::all();
		
		$weeks = $calctime/$usertime[0]['user_time'];;
		$future = Carbon::now()->addWeeks($weeks);

		return view('statistik', ['projects' => $projects,'users' => $users,'projecttime'=>$projecttime,'future'=>$future,'weeks'=>$weeks]);	
	}
	
	public function show($id)
	{
		$projects = Project::where('status',1)->get();
		$todo = Todo::where('project_id',$id)->where('status',0)->with('project')->with('milestone')->with('workSum')->get();
		return view('statistik', ['todos' => $todo]);
	}
}
