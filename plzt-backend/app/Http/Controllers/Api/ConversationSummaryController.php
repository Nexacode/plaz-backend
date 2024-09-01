<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProjectConversationSummary;

class ConversationSummaryController extends Controller
{
    public function show($id)
    {	
        return response()->json(ProjectConversationSummary::with('todo')->find($id), 201);
    }
    
    public function store(Request $request)
    {	
        return response()->json(ProjectConversationSummary::create($request->all()), 201);
    }

}
