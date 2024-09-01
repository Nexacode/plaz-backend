<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Auth;

class UserSettingController extends Controller
{
    public function store(Request $request)
    {
    	$data = $request->all();
    	$User = Auth::user();
    	$User = json_decode($User, true); 
    	  
    	$data['name'] = $User['token']['name']; 	
    	//$data['email'] = $User['token']['email']; 
    	$data['keycloak_id'] = $User['token']['sub']; 
    	
    	$user = User::updateOrCreate(['name'=>$data['name'],'keycloak_id'=>$data['keycloak_id']],$data);
    	
    	//$days[] = {'day' => 1,'hours' => 4},{'day' => 2,'hours' => 5};
    	
    	$user->times()->delete();
    	$user->times()->createMany($data['days']); 
    	
    	return response()->json($data,201);
    }
}
