<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Milestone;
use Auth;

class MilestoneController extends Controller
{
    public function index()
    {    	
    	return response()->json(Milestone::with('todo')->orderBy('priority')->get(), 201);
    }
    
    public function store(Request $request)
    { 
		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $request->request->add(['user_id' => $UserId,'user_name' => $UserName]);
               	
    	Milestone::create($request->all());
    	return response()->json($request,201);
    }
    
    public function show($id)
    {
        return response()->json([Milestone::find($id)], 201);
    }
    
    public function update(Request $request, $id)
    {
        return response()->json([Milestone::find($id)->update($request->all())], 201);
    }    
    
}
