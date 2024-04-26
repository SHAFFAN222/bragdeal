<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Portal_settings;
class Portal_settingsController extends Controller
{
    public function get()
    {    
        // var_dump('setting');die();
         $portal_settingss = portal_settings::get();
         $portal_settingss->transform(function ($portal_settingss) {
         $portal_settingss->logo = $portal_settingss->logo_url;
                    return $portal_settingss;
           });
        return response()->json(['message' => 'Get  portal_settings Successfully','data' => $portal_settingss], 200);
    }
    public function getById($id)
    {    
      $portal_settings = portal_settings::where('id',$id)->first();  
      return response()->json(['message' => 'Get id Successfully','data' => $portal_settings], 200);
    }
// public function getbyuser(Request $request) {
//     $user = Auth::user();
//     $portal_settings = portal_settings::where('user_id',$user->id)->get();
//     return response()->json(['message' => 'portal_settingss List','data' => $portal_settings], 200);
//     // return response()->json(['errors' => $portal_settings->errors()], 422);
// }
    
public function add(Request $request)
{
    $user = Auth::user();
    $rules = [       
        // Assuming user_id is an integer
        'title' => 'required|string', // Assuming title is a string
        'email' => 'required', // Assuming email is a string
        'phone' => ' required', // Assuming phone is a string
        'address' => 'required|string', // Assuming address is a string
        'color_scheme' => 'required|string', // Ensuring color scheme is a string
        'logo' => 'nullable|file|mimes:jpg,gif,jpeg,png,|max:2048', // File validation rules
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules); 
     if ($validator->fails()) { // If validation  
        return response()->json(['errors' => $validator->errors()], 422); // Return false
    }
  $portal_settings = new portal_settings();  
  $portal_settings->title = $request->input('title'); // Title
  $portal_settings->email = $request->input('email'); // Email
  $portal_settings->phone = $request->input('phone'); // Number
  $portal_settings->address = $request->input('address'); // Address
  $portal_settings->color_scheme = $request->input('color_scheme'); // Color scheme 
  $portal_settings->logo = $request->input('logo'); // Logo
  if ($request->hasFile('logo')) { // File
    $logo = $request->file('logo'); 
    $logoPath = $logo->store('logos'); // Store the file
    $portal_settings->logo = $logoPath;  
}

    $portal_settings->user_id = $user->id;
    $portal_settings->save();
 
    return response()->json(['message' => 'Create portal_settings Successfully','data' => $portal_settings], 200);
}

public function update(Request $request)
{
    // Find the portal_settings that belongs to the authenticated user
    $portal_settings = portal_settings::find($request->id);
    if (!$portal_settings) {
        return response()->json(['error' => 'portal_settings not found'], 404);
    }

    // Validation rules
    $rules = [
        'title' => 'sometimes|required|string', // 
        'email' => 'required', // Assuming email is a string
        'phone' => ' required', // Assuming phone is a string
        'address' => 'required|string', // Assuming address is a string
        'color_scheme' => 'required|string', 
        'logo' => 'nullable|file',
    
    ];

    // Validate the request
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Update portal_settings fields if they exist in the request
    if ($request->has('title')) {
        $portal_settings->title = $request->input('title');
    }
    if ($request->has('email')) {
        $portal_settings->email = $request->input('email');
    }
    if ($request->has('phone')) {
        $portal_settings->phone = $request->input('phone');
    }
    if ($request->has('address')) {
        $portal_settings->address = $request->input('address');
    }
    if ($request->has('color_scheme')) {
        $portal_settings->color_scheme = $request->input('color_scheme');
    }
    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $portal_settings->logo = $logo->store('logos');
    }
   
    // Save the updated portal_settings
    $portal_settings->save();
        
    
    // Return success response
    return response()->json(['message' =>'portal_settings updated successfully' ,'data' => $portal_settings], 200);
}
    public function delete($id)
{ 
    $portal_settings = portal_settings::find($id);
    if (!$portal_settings) {
        return response()->json(['error' => 'portal_settings not found'], 404);
    }

    $portal_settings->delete();

    return response()->json(['message' => 'portal_settings deleted successfully'], 200);
}
}