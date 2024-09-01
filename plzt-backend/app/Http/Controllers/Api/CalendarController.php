<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Todo;
use App\Models\Category;
use App\Http\Resources\CalendarResource;

use App\Http\Resources\CalendarProjectEventsResource;
use App\Http\Resources\CalendarProjectResourceResource;

use Carbon\Carbon;
use Auth;

class CalendarController extends Controller
{
	public function index()
	{
		//return Auth::decodededtoken();

        //$data = $request->all();
        $url = env('KEYCLOAK_URL') . 'admin/realms/' .env('KEYCLOAK_REALM') . '/groups/' .env('KEYCLOAK_EMPLOYEE_GROUP') . '/members';
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . Auth::decodededtoken()
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $error = json_decode($err);
        $response = json_decode($output);

        if ($err) {
            return response()->json($error, 201);
        }
        if ($response) {
            return response()->json($response, 201);
        }
	}

    public function show($id)
    {
       //return response()->json(Todo::where('project_id',4)->get(), 201);
        return CalendarResource::collection(Project::orderByRaw('ISNULL(priority), priority ASC')->get());
    }

    public function update(Request $request,$id)
    {
    	Todo::find($id)->update([]);
    	return $request;
    	return Carbon::parse($request->start,'UTC');
    }

    public function getProjectResources()
    {
    	$projects = Project::orderByRaw('ISNULL(priority), priority ASC')->where('status',1)->get();
    	return CalendarProjectResourceResource::collection($projects);
    }

    public function getProjectEvents()
    {
    	$events = Category::all();
    	return CalendarProjectEventsResource::collection($events);
    }
}
