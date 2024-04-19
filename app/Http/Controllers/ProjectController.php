<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
class ProjectController extends Controller
{

    public function get_projects($id)
    
    {    
        $projects = Project::where('user_id', $id)->get();
      return response()->json($projects);

    }

    
    public function projects_store(Request $request)
    {
    // var_dump('daniyal');die();
        $rules =[
            'user_id' =>'required|',
            'title' => 'required|',
            'start_date' =>'required|date',
            'end_date' =>'required|date',
            'type' =>'required|',
            'attachment' =>'required|',
            'status' =>'required|in:active,inactive'
        ];

   // Validate the request
   $validator = Validator::make($request->all(), $rules);

   if ($validator->fails()) {
       // Return validation errors with status code 422
       return response()->json(['errors' => $validator->errors()], 422);
   }
        
        $project = new Project();
        $project->user_id = request('user_id');
        $project->title = request('title');
        $project->start_date = request('start_date');
        $project->end_date = request('end_date');
        $project->type = request('type');
        $project->attachment = request('attachment');
        $project->status = request('status');
        $project->save();

        return response()->json($project, 201);
    }
}