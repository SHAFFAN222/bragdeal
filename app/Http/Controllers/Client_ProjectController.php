<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Client_Project;
use Illuminate\Support\Facades\Auth;
class Client_ProjectController extends Controller
{
    
    public function get(){
        // dd('Project');
        $client_projects = Client_Project::get();
        $client_projects->transform(function ($client_projects) {
            $client_projects->attachment = $client_projects->attachment_url;
            return $client_projects;
        });
        return $client_projects;
    }
    
  
// public function getbyuser(Request $request) {
//     $user = Auth::user();
//     $client_project = client_project::where('user_id',$user->id)->get();
//     return response()->json(['message' => 'client_projects List','data' => $client_project], 200);
//     // return response()->json(['errors' => $client_project->errors()], 422);
// }
    
public function add(Request $request)
{
    dd('add', $request);
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

    // Create and save the client_project
    $client_project = new client_project();
    $client_project->title = $request->input('title');
    $client_project->start_date = $request->input('start_date');
    $client_project->end_date = $request->input('end_date');
    $client_project->type = json_encode($request->input('type'));
    $client_project->attachment = $attachmentPath;
    $client_project->user_id = $user->id;
    $client_project->save();

    // Update attachment URL if it exists
    if ($client_project->attachment) {
        $client_project->attachment = url('/' . $client_project->attachment);
    }

    return response()->json([
        'client_project' => $client_project
    ], 201);
}

public function update(Request $request)
{
    // Find the client_project that belongs to the authenticated user
    $client_project = client_project::find($request->id);
    if (!$client_project) {
        return response()->json(['error' => 'client_project not found'], 404);
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

    // Update client_project fields if they exist in the request
    if ($request->has('title')) {
        $client_project->title = $request->input('title');
    }
    if ($request->has('start_date')) {
        $client_project->start_date = $request->input('start_date');
    }
    if ($request->has('end_date')) {
        $client_project->end_date = $request->input('end_date');
    }
    if ($request->has('type')) {
        $client_project->type = $request->input('type');
    }
    if ($request->has('description')) {
        $client_project->description = $request->input('description');
    }
    if ($request->has('category')) {
        $client_project->category = $request->input('category');
    }
    if ($request->hasFile('attachment')) {
        $client_project->attachment = $request->file('attachment')->store('attachments');
    }
    // Save the updated client_project
    $client_project->save();
        
    if ($client_project->attachment) {
        $client_project->attachment = url('/' . $client_project->attachment); 
     }
    // Return success response
    return response()->json(['client_project' => $client_project], 200);
}
public function delete($id)
{ 
    $client_project = client_project::find($id);
    if (!$client_project) {
        return response()->json(['error' => 'client_project not found'], 404);
    }

    $client_project->delete();

    return response()->json(['message' => 'client_project deleted successfully'], 200);
}

}