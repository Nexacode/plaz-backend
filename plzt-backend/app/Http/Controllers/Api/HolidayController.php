<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Holiday;
use App\Models\User;
use App\Models\TodoEvent;
use App\Models\SetSendEmail;

use App\Http\Resources\HolidayResource;

use Auth;
use PDF;
use Mail;

use Carbon\Carbon;

class HolidayController extends Controller
{

	public function index()
	{
		$holidays = Holiday::query();
		return HolidayResource::collection($holidays->where('status','!=',2)->get());
	}

    public function store(Request $request)
    {

        $data[] = json_decode($request->value,true);
        $data = $data[0];
        
    	$User = Auth::user();
    	$User = json_decode($User, true);
    	
    	$data['user_id'] = $User['token']['sub'];
    	$data['user_name'] = $User['token']['name'];
    	$data['contraction'] = mb_substr($User['token']['given_name'], 0, 2) . mb_substr($User['token']['family_name'], 0, 2);
    	
		$data['alldays'] = User::where('keycloak_id',$User['token']['sub'])->select('holidays')->get();
		$data['days_approved'] = Holiday::where('user_id',$User['token']['sub'])->where('status',1)->where('holiday_year','=',Carbon::now()->year)->sum('days');
		//$data['remaining_days'] = $data['alldays'] - $days_approved;

    	if($request->user_id){
    		$data['user_id'] = $request->user_id;
    		$data['user_name'] = $request->username;
    		$arr = explode(' ',trim($data['user_name']));	
    		$data['contraction'] = mb_substr($arr[0], 0, 2) . mb_substr($arr[1], 0, 2);
    		$data['contraction'] = $data['contraction'] . "/ eingereicht";
    	}
    	
    	$filePath = $request->signature->store('signatures');
    	$data['signature'] = storage_path("app/{$filePath}");
    	
    	$pdf = PDF::loadView('pdf.holiday', $data);
    	$user = User::where('keycloak_id',$User['token']['sub'])->first();
    	$emails = SetSendEmail::where('prefix', 'holiday_alert')->pluck('email')->toArray();
    	array_push($emails, $user->email);

      		Mail::send('holiday_alert', $data, function($message) use ($pdf,$user,$emails){
        		$message->to($emails)->subject('Urlaubsantrag')
        			->from("info@power4-its.com","power4-its GmbH")
         		 	->attachData($pdf->output(), 'urlaubsantrag.pdf');
      		}); 
    	
    	$data['color'] = $user->color;
    	//$data['start'] = $request->start . "";
    	//$data['end'] = $request->end . "";
    	$data['from'] = $data['start'];
    	$data['to'] = $data['end'];
    	$data['signature'] = $filePath;
    	$data['status'] = 0;
    	
    	$event = [
    		"start" => $data['start'],
    		"end" => $data['end'] . "T23:59",
    		"user_id" => $data['user_id'],
    		"title" => "Urlaub beantragt",
    		"color" => "#D8D8D8",
    		"color_border" => "#D8D8D8"
    	];
    	$event = TodoEvent::create($event);
    	
    	$data['todo_event_id'] = $event->id;
    	
    	$response = Holiday::create($data);
    	

    	
    	return response()->json($data,201);
    }
	
	public function listUserHolidays()
	{
    	$user = json_decode(Auth::user(), true);
    	
		$holidays = Holiday::where('user_id',$user['token']['sub'])->where('holiday_year','=',Carbon::now()->year)->get();
		return response()->json($holidays,201);
	}
	
	public function getUserHolidays()
	{
    	$user = json_decode(Auth::user(), true);
    	
		$days = User::where('keycloak_id',$user['token']['sub'])->select('holidays')->get();
		$days_approved = Holiday::where('user_id',$user['token']['sub'])->where('status',1)->where('holiday_year','=',Carbon::now()->year)->sum('days');
		$days_not_approved = Holiday::where('user_id',$user['token']['sub'])->where('status','!=',2)->where('holiday_year','=',Carbon::now()->year)->sum('days');
		
		$days_left = $days[0]['holidays']-$days_approved;
		$days_left_after_approval = $days[0]['holidays']-$days_not_approved;
		
		$days = [];
		$days['days_left'] = $days_left;
		$days['days_left_after_approval'] = $days_left_after_approval;
		
		return response()->json($days,201);
	}
	
	public function getUnapprovedHolidays()
	{
		$holidays = Holiday::where('status',0)->where('holiday_year','=',Carbon::now()->year)->get();		
		return response()->json($holidays,201);
	}
	
	public function approve(Request $request,$id)
    {
    	$holidays = Holiday::find($id);
    	$holidays->update($request->all());
    	
    	$user = User::where('keycloak_id',$holidays->user_id)->first();
    	$approved_by = json_decode(Auth::user(), true);
    	
    	if($request->status == 2){
    	
    		$data = [
    			"user" => $user->name,
    			"reason" => $request->reason
    		];
    		
      		Mail::send('holiday_declined', $data, function($message) use ($user){
        		$message->to($user->email, $user->name)->subject('Urlaubsantrag abgelehnt')
        			->from("info@power4-its.com","power4-its GmbH");
      		});
      		
      		TodoEvent::find($holidays->todo_event_id)->delete(); 
      		 
    	} else {
    	
    		$approved_days = Holiday::where('user_id',$holidays->user_id)->where('status',1)->where('holiday_year','=',Carbon::now()->year)->sum('days');
    		
    		$data = [
    			"user" => $user->name,
    			"start" => $holidays->start,
    			"end" => $holidays->end,
    			"alldays" => $user->holidays,
    			"days_approved" => Holiday::where('user_id',$holidays->user_id)->where('status',1)->where('holiday_year','=',Carbon::now()->year)->sum('days'),
    			"days" => $holidays->days,
    			"signature" => storage_path("app/{$holidays->signature}"),
    			"approved_by" => $approved_by['token']['name'],
    			"approved_by_sig" => storage_path("app/signature.png")
    		];
    		
    		$pdf = PDF::loadView('pdf.holiday_accepted', $data);
    		
    		$emails = SetSendEmail::where('prefix', 'holiday_accepted')->pluck('email')->toArray();
    		array_push($emails, $user->email);
        	    		
      		Mail::send('holiday_accepted', $data, function ($message) use ($pdf,$emails,$user) {
        		$message->to($emails)->subject('Urlaub genehmigt - ' . $user->name)
        			->from("info@power4-its.com","power4-its GmbH")
        			->attachData($pdf->output(), 'urlaubsantrag.pdf');
      		});    
      		
      		TodoEvent::find($holidays->todo_event_id)->update(["title"=>"Urlaub"]); 		
    	}
    	
    	return response()->json("app/" . $holidays->signature,201);
    }
    
	public function holidayOverview()
    {
    	return response()->json(true,201);
    }

}
