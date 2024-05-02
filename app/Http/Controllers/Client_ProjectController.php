<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Client_Project;
use Illuminate\Support\Facades\Auth;
class Client_ProjectController extends Controller
{
    public function get_projects()
    
    {    
    dd('Project');
        $client_projects = Client_Project::get();
        $client_projects->transform(function ($client_projects) {
            $client_projects->attachment = $client_projects->attachment_url;
                    return $client_projects;
           });
        return response()->json(['message' => 'Get  Project Successfully','data' => $client_projects], 200);

    }
    
   
    
public function add(Request $request)
{
    // Ensure user is authenticated
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    // Validation rules
    $rules = [
        'title' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'type' => 'required|string',
        'attachment' => 'nullable|file|mimes:jpg,gif,jpeg,png,doc,xls,docx,xlsx,pdf|max:2048',
        
         
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Store attachment if provided
    $attachmentPath = null;
    if ($request->hasFile('attachment')) {
        $attachmentPath = $request->file('attachment')->store('attachments');
    }

    // Create and save the project
    $project = new Project();
    $project->title = $request->input('title');
    $project->start_date = $request->input('start_date');
    $project->end_date = $request->input('end_date');
    $project->type = json_encode($request->input('type'));
    $project->description = $request->input('description');
    $project->category = json_encode($request->input('category'));
    $project->attachment = $attachmentPath;
    $project->user_id = $user->id;
    $project->save();

    // Update attachment URL if it exists
    if ($project->attachment) {
        $project->attachment = url('/' . $project->attachment);
    }

    return response()->json([
        'Project' => $project
    ], 201);
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
        'description' => 'required|string',
        'category' => 'required|string',
    
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
    if ($request->has('description')) {
        $project->description = $request->input('description');
    }
    if ($request->has('category')) {
        $project->category = $request->input('category');
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
    return response()->json(['project' => $project], 200);
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