<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
class ProjectController extends Controller
{
    public function get_projects()
    
    {    
        $projects = Project::get();
        return response()->json(['message' => 'Get  Project Successfully'], 200);

    }
    public function get($id)
    
    {    
      $project = Project::where('id',$id)->first();  
      return response()->json(['message' => 'Get id Successfully'], 200);
    }
public function getbyuser(Request $request,$user_id) {
   
    $project = Project::where('user_id',$user_id)->get();
    // dd($project); 
    return response()->json(['message' => 'Get  user Successfully'], 200);
    // return response()->json(['errors' => $project->errors()], 422);
}
    
public function add(Request $request)
{
  
    $rules = [
        'user_id' => 'required|integer', // Assuming user_id is an integer
        'title' => 'required|string', // Assuming title is a string
        // 'start_date' => 'date',
        // 'end_date' => 'date|after_or_equal:start_date', // Ensuring end_date is after or equal to start_date
        'type' => 'required|string', // Assuming type is a string
        'attachment' => 'required|string', // Assuming attachment is a string (file path or URL)
        'status' => 'required|in:active,inactive'
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);
    // dd($validator);
     if ($validator->fails()) {
     
        return response()->json(['errors' => $validator->errors()], 422);
    }
    $project = new Project();
    $project->user_id = $request->input('user_id');
    $project->title = $request->input('title');
    $project->start_date = $request->input('start_date');
    $project->end_date = $request->input('end_date');
    $project->type = $request->input('type');
    $project->attachment = $request->input('attachment');
    $project->status = $request->input('status');

    $project->save();

    return response()->json(['message' => 'Create Project Successfully'], 200);
}
public function update(Request $request , $id)
{
    // Validation rules
    $rules = [
        'user_id' => 'sometimes|required|integer', // Allow user_id to be optional, but if provided, it must be an integer
        'title' => 'sometimes|required|string', // Allow title to be optional, but if provided, it must be a string
        'start_date' => 'sometimes|date',
        'end_date' => 'sometimes|date|after_or_equal:start_date',
        'type' => 'sometimes|required|string',
        'attachment' => 'sometimes|required|string',
        'status' => 'sometimes|required|in:active,inactive'
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

  
    $project = Project::findOrFail($id);

   
    if ($request->has('user_id')) {
        $project->user_id = $request->input('user_id');
    }
    if ($request->has('title')) {
        $project->title = $request->input('title');
    }
    if ($request->has('start_date')) {
        $project->start_date = $request->input('start_date');
    }
    if ($request->has('end_date')) {
        $project->end_date = $request->input('end_date');
    }
    if ($request->has('type')) {
        $project->type = $request->input('type');
    }
    if ($request->has('attachment')) {
        $project->attachment = $request->input('attachment');
    }
    if ($request->has('status')) {
        $project->status = $request->input('status');
    }
    $project->save();
    return response()->json(['message' => 'Update Project Successfully'], 200);
}
public function delete($id)
{ 
    $project = Project::find($id);
    if (!$project) {
        return response()->json(['error' => 'Project not found'], 404);
    }

    $project->delete();

    return response()->json(['message' => 'Project deleted successfully'], 200);
}

}