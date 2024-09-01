<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Milestone;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
    	$Category = Category::where('in_category_id',null)->get();
    	$CategoryCount = $Category->withSum('todo','estimated_time')->where('done',0)->get();
    	
    	return CategoryResource::collection(Category::where('in_category_id',null)->get());
    	//return response()->json(Category::where('in_category_id',null)->get(),201);
    }   
    
    public function store(Request $request)
    {
    	$category = Category::create($request->all());
    	$request->request->add(['milestone' => $request->name,'category_id' => $category->id]);
    	$milestone = Milestone::create($request->all());
    	return response()->json($category,201);
    }
    
    public function show($project)
    {
    	$Category = Category::where('in_category_id',null)->where('project_id',$project)->get();
    	return CategoryResource::collection($Category);
    }
    
    public function done($project)
    {
    	$Category = Category::where('in_category_id',null)->where('project_id',$project)
    					->whereHas('todo',function($q){
    						$q->where('status',1);
    					})
    				->get();
    	return CategoryResource::collection($Category);
    }    
    
    public function milestones($category)
    {
    	$Milestone = Milestone::with(['todo.categories'])->where('category_id',$category)->orderBy('priority')->get();
    	return response()->json($Milestone, 201);
    	//return CategoryResource::collection(Category::where('in_category_id',null)->where('project_id',$project)->get());
    }
    
    public function update(Request $request, Category $category)
    {
    	$Category = $category->update($request->all());
    	return response()->json($Category, 201);
    }
}
