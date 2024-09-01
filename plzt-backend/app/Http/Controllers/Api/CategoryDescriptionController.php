<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\CategoryDescriptionResource;
use App\Models\CategoryDescription;
use Auth;

class CategoryDescriptionController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
		$User = Auth::user();
        $User = json_decode($User, true);
        $UserId = $User['token']['sub'];
        $UserName = $User['token']['name'];
        
        $request->request->add(['user_id' => $UserId,'user_name' => $UserName]);
            
        return response()->json(CategoryDescription::firstOrCreate($request->all()),201);
    }


    public function show($id)
    {
        return response()->json(CategoryDescription::where('category_id',$id)->latest('id')->first(),201);
    }
    
    public function showAllDescriptions($id)
    {
        //return response()->json(CategoryDescription::where('category_id',$id)->get(),201);
        return CategoryDescriptionResource::collection(CategoryDescription::where('category_id',$id)->get());
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
