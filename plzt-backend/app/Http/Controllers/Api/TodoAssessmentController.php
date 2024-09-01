<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TodoAssessment;
use App\Models\Todo;
use Auth;
use Mail;

use Carbon\Carbon;

class TodoAssessmentController extends Controller
{
    public function store (Request $request)
    {
    
		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $request->request->add(['user_id' => $UserId,'user_name' => $UserName]);
        
        TodoAssessment::create($request->all());
        
        $todo = Todo::find($request->todo_id);
        
        if($todo->estimated_time < $request->time){

        	$message = "

Ursprüngliche Schätzung: " . $todo->estimated_time . "
Schätzung Mitarbeiter: " . $request->time ."
Mitarbeiter: " . $UserName ."
Todo id: " . $request->todo_id;

        	$from = "info@power4-its.com";
        	$to = "sascha.koziellek@power4-its.de";
        	$subject = "Schätzung höher als Ursprung";

        	Mail::raw($message, function ($mail) use ($to, $from, $subject) {
            	$mail->from($from)->to($to)->subject($subject);
        	});      
        }
    
    	return response()->json($todo,201);
    }
}
