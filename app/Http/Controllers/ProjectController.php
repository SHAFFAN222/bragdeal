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
      return response()->json($projects);

    }
    public function get_project($id)
    
    {    
      $project = Project::where('id',$id)->first();  
     return response()->json(['errors' => $project->errors()], 422);
    }
public function getbyuser() {
    $project = Project::where('uesr_id',$id)->get();
    return response()->json(['errors' => $project->errors()], 422);
}
    
public function add(Request $request)
{
   
    $rules = [
        'user_id' => 'required|integer', // Assuming user_id is an integer
        'title' => 'required|string', // Assuming title is a string
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date', // Ensuring end_date is after or equal to start_date
        'type' => 'required|string', // Assuming type is a string
        'attachment' => 'required|string', // Assuming attachment is a string (file path or URL)
        'status' => 'required|in:active,inactive'
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

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

    // Save the project
    $project->save();

    return response()->json($project, 201);
}
public function update(Request $request, $id)
{
    // Validation rules
    $rules = [
        'user_id' => 'sometimes|required|integer', // Allow user_id to be optional, but if provided, it must be an integer
        'title' => 'sometimes|required|string', // Allow title to be optional, but if provided, it must be a string
        'start_date' => 'sometimes|required|date',
        'end_date' => 'sometimes|required|date|after_or_equal:start_date',
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
    return response()->json($project, 200);
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