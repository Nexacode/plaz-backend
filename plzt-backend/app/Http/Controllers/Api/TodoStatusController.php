<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Todo;
use Auth;

class TodoStatusController extends Controller
{
    public function show($id)
    {
    	$Todo = Todo::find($id);
    	
    	if($Todo->status == 1){
    		$Todo->update(['status' => 0]);
    	} 
    	elseif($Todo->status == 0){
    		$Todo->update(['status' => 1]);
    	}   	
        return response()->json($Todo->status, 201);
    }
    
    public function update(Request $request, $id)
    {
        $user = json_decode(Auth::user(), true);
        $user_id = $user['token']['sub'];    
        
        $is_active = false;    
        
    	$active_todo = Todo::where('active',1)->where('user_id',$user_id)->where('status',0)->count();
    	
    	if($active_todo == 0){
    		$is_active = true;
    		Todo::find($id)->update($request->all());
    	} 
    	
    	if($active_todo > 0 && $request->active == 0){
    		Todo::find($id)->update($request->all());
    	}
    	
    	
    	return response()->json($is_active, 201);
    }    
}
