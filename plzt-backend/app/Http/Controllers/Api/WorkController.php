<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Work;
use App\Models\Todo;
use App\Models\Ticket;
use App\Models\SetSendEmail;
use App\Models\TodoApproveInformation;
use Auth;

use Carbon\Carbon;
use Mail;

class WorkController extends Controller
{
	public function index()
	{
		$Month = Carbon::now()->format('m');
		$Year = Carbon::now()->format('Y');
		
		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];	
		return response()->json(Work::where('user_id',$UserId)->whereMonth('date',$Month)->whereYear('date',$Year)->with('todo')->with('category')->with('project')->orderBy('date')->get(),201);
	}
	
    public function store (Request $request)
    {
    
		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $request->request->add(['user_id' => $UserId,'user_name' => $UserName]);
        
        if($request->done){
        
        	$todo = Todo::with('statusname')->find($request->todo_id);
 
         	$data = [
        		'user_name' => $UserName,
        		'todo' => $todo->todo,
        		'status' => $todo->statusname->name
        	];
 
        	$emails = SetSendEmail::where('prefix', 'done_todo')->pluck('email')->toArray();
        	
        	Mail::send('done_todo', $data, function($message) use ($emails, $request) {
            	$message->to($emails)->subject('Todo erledigt');
            	$message->from("info@power4-its.com", "info@power4-its.com");
        	}); 
        	
        	Todo::find($request->todo_id)->update(['status' => 1,'active' => 0,'todo_status_id' => $request->todo_status_id]);
        }
        
        if($request->checking_status){
        	$todo = Todo::with('statusname')->find($request->todo_id);
        	$todo->approveInformations()->create(["information"=>$request->approve_information,"user_id"=>$request->user_id,"user_name"=>$request->user_name]);
        	$todo->update(['todo_status_id' => $request->todo_status_id,'active' => 0]);
        	
        	
        	$data = [
        		'user_name' => $UserName,
        		'todo' => $todo->todo,
        		'status' => $todo->statusname->name,
        		'information' => $request->approve_information
        	];
        	
        	$emails = SetSendEmail::where('prefix', 'approve_todo')->pluck('email')->toArray();
        	
        	Mail::send('approve_todo', $data, function($message) use ($emails, $request) {
            	$message->to($emails)->subject('Todo auf in Prüfung geändert');
            	$message->from("info@power4-its.com", "info@power4-its.com");
        	}); 
        }
        
        if($request->reset){
        	//Todo::find($request->todo_id)->update(['status' => 2]);
        }
        
        $todo = Todo::with('project')->find($request->todo_id);
        
        if($request->overtime && $request->time != 0){
        	$data = [
        		'user_name' => $UserName, 
        		'user_id' => $UserId, 
        		'estimation' => $request->estimation,
        		'information' => $request->information,
        		'todo' => $todo->todo,
        		'project' => $todo->project->name
        	];
        	$overtime = $todo->overtimes()->create($data);
        	
			$emails = SetSendEmail::where('prefix', 'overtime')->pluck('email')->toArray();

        	Mail::send('overtime', $data, function($message) use ($emails, $request) {
            	$message->to("sascha.koziellek@power4-its.de")->subject('Todo über der Zeit');
            	$message->from("info@power4-its.com", "info@power4-its.com");
        	});          	
        	
        }

        $work_time = Work::where('todo_id',$request->todo_id)->sum('time');
        
        if($todo->estimated_time != 0){
        	$percent_done = round((($work_time+$request->time)*100)/$todo->estimated_time);
        } else {
        	$percent_done = $todo->percent_done;
        }
        
        $todo->update([
        	'checking_status' => $request->checking_status,
        	'percent_done' => $percent_done,
        	'todo_status_id' => $request->todo_status_id
        ]);
                
        Work::create($request->all());
    
    	return response()->json(true,201);
    }
    
    public function getApprovalInformation($id)
    {
    	$infos = TodoApproveInformation::where('todo_id',$id)->get();
    	return response()->json($infos,201);
    }
    
    public function storeTicketWork (Request $request)
    {
    
		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $request->request->add(['user_id' => $UserId,'user_name' => $UserName]);
        
        if($request->done){
        	$ticket = Ticket::find($request->ticket_id);
        	$ticket->update(['status' => 2,'solution'=>$request->solution]);
        	$ticket->history()->create(['ticket_status_id' => 2,'user_name' => $UserName,'user_id' => $UserId]);
        }
        
    	$time = Work::where('ticket_id',$request->ticket_id)->sum('time');
    	$time = $time + $request->time;
    	
    	if($time > 1.5){
    	
    		$ticket = Ticket::find($request->ticket_id);
    		
    		$data = [
    			"user" => $UserName,
    			"ticket_id" => $request->ticket_id,
    			"ticket_user" => $request->client,
    			"ticket_subject" => $ticket->subject,
    			"ticket_time" => $time,
    			"ticket_status" => $ticket->status,
    		];
    		
      		Mail::send(['text'=>"ticket_alert"], $data, function($message) {
         		$message->to('sascha.koziellek@power4-its.de', 'Sascha Koziellek')->subject('Ticket - Out of Time');
         		$message->from('info@power4-its.com','PLZT TOOL');
      		});    		
    	}
       // Todo::find($request->todo_id)->update(['checking_status' => $request->checking_status]);
       
       	if(!empty($request->time)){
       		Work::create($request->all()); 
       	}
               
    	return response()->json(true,201);
    }
    
    public function userWorks(Request $request)
    {
    	$Month = Carbon::now()->format('m');
    	$Year = Carbon::now()->format('Y');
    	return response()->json(Work::where('user_id',$request->user_id)->whereMonth('date',$Month)->whereYear('date',$Year)->with('todo')->with('category')->with('project')->get(),201);
    }
}
