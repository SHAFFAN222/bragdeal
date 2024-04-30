<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
class ProjectController extends Controller
{

    
    public function get_projects()
    
    {    
        $projects = Project::get();
        $projects->transform(function ($projects) {
            $projects->attachment = $projects->attachment_url;
                    return $projects;
           });
        return response()->json(['message' => 'Get  Project Successfully','data' => $projects], 200);

    }
    
    public function get($id)
    
    {    
      $project = Project::where('id',$id)->first();  
      return response()->json(['message' => 'Get id Successfully','data' => $project], 200);
    }
public function getbyuser(Request $request) {
    $user = Auth::user();
    $project = Project::where('user_id',$user->id)->get();
    return response()->json(['message' => 'Projects List','data' => $project], 200);
    // return response()->json(['errors' => $project->errors()], 422);
}
    
public function add(Request $request)
{
    
    $user = Auth::user();
      
    $rules = [
        
        // Assuming user_id is an integer
        'title' => 'required|string', // Assuming title is a string
        'start_date' => 'required',
        'end_date' => ' required',// Ensuring end_date is after or equal to start_date
        'type' => 'required|string', // Assuming type is a string
        'attachment' => 'nullable|file|mimes:jpg,gif,jpeg,png,doc,xls,docx,xlsx,pdf|max:2048', // File validation rules
     
       
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);
    // dd($validator);
     if ($validator->fails()) {
     
        return response()->json(['errors' => $validator->errors()], 422);
    }
    $project = new Project();
    // $project->user_id = $request->input('user_id');
    $project->title = $request->input('title');
    $project->start_date = $request->input('start_date');
    $project->end_date = $request->input('end_date');
    $project->type = $request->input('type');
    $project->attachment = $request->file('attachment')->store('attachments');
    $project->user_id = $user->id;
    $project->save();
    if ($project->attachment) {
        $project->attachment = url('/' . $project->attachment); 
     }
    return response()->json(['message' => 'Create Project Successfully','data' => $project], 200);
}

public function update(Request $request)
{
    // Find the project that belongs to the authenticated user
    $project = Project::find($request->id);
    if (!$project) {
        return response()->json(['error' => 'Project not found'], 404);
    }

    // Validation rules
    $rules = [
        'title' => 'sometimes|required|string',
        'start_date' => 'required',
        'end_date' => 'required',
        'type' => 'sometimes|required|string',
        'attachment' => 'nullable|file',
    
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Update project fields if they exist in the request
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
    if ($request->hasFile('attachment')) {
        $project->attachment = $request->file('attachment')->store('attachments');
    }
    // Save the updated project
    $project->save();
        
    if ($project->attachment) {
        $project->attachment = url('/' . $project->attachment); 
     }
    // Return success response
    return response()->json(['message' =>'Project updated successfully' ,'data' => $project], 200);
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