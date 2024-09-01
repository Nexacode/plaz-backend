<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TodoAssessment;
use App\Models\Todo;

use Auth;

class AssessmentController extends Controller
{
    public function store (Request $request)
    {

		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];

        $Todo =  Todo::find($request->todo_id)->update(['start' => $request->date]);
        //$CustomerAcquisation = CustomerAcquisation::find($id)->update($request->all());

        $request->request->add(['user_id' => $UserId,'user_name' => $UserName]);

        $Assessment = TodoAssessment::create($request->all());



    	return response()->json($Todo,201);
    }
}
