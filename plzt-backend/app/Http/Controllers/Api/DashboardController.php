<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Todo;
use App\Models\Work;
use App\Models\TodoRework;
use App\Models\User;
use App\Models\SetSendEmail;

use Auth;
use Mail;

use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
    	$todo = array();
        $todo['active'] = Todo::where('active', 1)->with('project')->with('lastAssessment')->withSum('work','time')->get();
        $todo['last_finished'] = Todo::where('status', 1)->with('project')->with('lastAssessment')->withSum('work','time')->orderBy('updated_at','DESC')->limit(10)->get();
		$todo['last_work'] = Work::with('todo')->with('project')->with('assessment')->orderBy('id','DESC')->limit(5)->get();
		$todo['in_examination'] = Todo::where('checking_status', 1)->with('project')->with('lastAssessment')->withSum('work','time')->orderBy('updated_at','DESC')->get();
		$todo['in_rework'] = Todo::where('checking_status', 2)->with('project')->with('lastAssessment')->with('deadline')->withSum('work','time')->orderBy('updated_at','DESC')->get();
		$todo['in_final'] = Todo::where('checking_status', 3)->where('todo_status_id','>=',8)->where('todo_status_id','<',10)->with('project')->with('deadline')->with('lastAssessment')->withSum('work','time')->orderBy('updated_at','DESC')->get();
		$todo['in_acceptance'] = Todo::where('checking_status', 3)->where('todo_status_id', 9)->with('project')->with('lastAssessment')->with('deadline')->withSum('work','time')->orderBy('updated_at','DESC')->get();
		$todo['overtime'] = Todo::where('todo_status_id','<',10)->with('project')->with('lastAssessment')->with('deadline')->withSum('work','time')->orderBy('updated_at','DESC')->get();
		$todo['meeting'] = Todo::where('todo_status_id', 16)->with('project')->with('lastAssessment')->with('deadline')->withSum('work','time')->orderBy('updated_at','DESC')->get();
		//$Todo['last_work']['sum_time'] = Work::where($Todo['last_work'][0]['todo_id'])->sum('time');

        return response()->json([$todo], 201);
    }
    
    public function userRework()
    {
    	return response()->json(true, 201);
    }
    
    public function userOvertime()
    {
    	$user = json_decode(Auth::user(), true);

$now = Carbon::now();
$userId = $user['token']['sub'];

$todo = Todo::whereIn('todo_status_id', [1,2,3,4,5,6,7,8,10,12,13])
            ->where('deadline', '<', $now)
            ->where('user_id', $userId)
            ->with('project')
            ->withSum('work','time')
            ->with('lastAssessment')
            ->with('lastDeadline')
            ->get();

    	return response()->json($todo, 201);
    }
    
    public function overtime()
    {
    	$todo = Todo::whereIn('todo_status_id', [1,2,3,4,5,6,7,8,10,12,13])->where('deadline', '<', Carbon::now())->with('project')->get();
    	return response()->json($todo, 201);
    }
    
    public function storeRework(Request $request)
    {
    	$user = json_decode(Auth::user(), true);

    	$request->merge([
                'user_id' => $user['token']['sub'],
                'user_name' => $user['token']['name'],
        ]);
    	
    	$rework = TodoRework::create($request->all());
    	
    	$todo = Todo::find($request->todo_id);
    	$todo->deadlines()->create(['deadline' => $request->deadline, 'todo_status_id' => $todo->todo_status_id]);
        	
        $email = User::where('keycloak_id',$todo->user_id)->select('email')->first();
        $email_from = User::where('keycloak_id',$user['token']['sub'])->select('email')->first();
        
        $data = [
        	'todo' => $todo->todo,
        	'description' => $request->description,
        	'user_name' => $todo->user_name,
        	'deadline' => $request->deadline
        ];
        
        $emails = SetSendEmail::where('prefix', 'rework')->pluck('email')->toArray();
    	array_push($emails, $email->email);
        	
        Mail::send('rework', $data, function($message) use ($emails, $request, $email_from) {
        	$message->to($emails)->subject('Nacharbeit notwendig');
            $message->from($email_from->email, $request->user_name);
        });
        
        $todo->update(['checking_status' => 2]);
        //$todo->histories()->create(['todo_status_id' => 2]);
    	
    	return response()->json($email->email, 201);
    }
    
    public function storeApproved(Request $request)
    {
    	$user = json_decode(Auth::user(), true);
    	
    	$request->merge([
                'user_id' => $user['token']['sub'],
                'user_name' => $user['token']['name'],
        ]);
        
        $todo = Todo::find($request->todo_id);
        $todo->update(['checking_status' => 3, 'todo_status_id' => $request->todo_status_id, 'percent_done' => 100]);
        
        $email = User::where('keycloak_id',$todo->user_id)->select('email')->first();
    	
    	$email_from = User::where('keycloak_id',$user['token']['sub'])->select('email')->first();
    	
        $data = [
        	'todo' => $todo->todo,
        	'description' => $request->description,
        	'user_name' => $todo->user_name,
        	'deadline' => $request->deadline,
        	'todo_status_id' => $request->todo_status_id
        ];
        
        $emails = SetSendEmail::where('prefix', 'approved')->pluck('email')->toArray();
    	array_push($emails, $email->email);
        	
        Mail::send('approved', $data, function($message) use ($emails, $request, $email_from) {
        	$message->to($emails)->subject('Todo erfolgreich geprüft');
            $message->from($email_from->email, $request->user_name);
        });
        
    	return response()->json($request, 201);
    }
    
}
