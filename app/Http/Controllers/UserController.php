<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Handle user signup.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {

        $rules = [
            'username' => 'required|string|unique:users',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'about' => 'required|string',
            'gender' => 'nullable|in:M,F,O',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8', // Minimum password length set to 8
            'role_id' => 'required|exists:roles,id',
            
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Return validation errors with status code 422
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user
        try {
            $user = User::create([
                'username' => $request->input('username'),
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'about' => $request->input('about'),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
            ]);




            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            // Return the exception message and status code 500
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle user login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

     public function login(Request $request)
     {
         $loginUserData = $request->validate([
             'email' => 'required|string|email',
             'password' => 'required|min:8'
         ]);
     
         $user = User::where('email', $loginUserData['email'])->first();
     
         if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
             return response()->json([
                 'message' => 'Invalid Credentials'
             ], 401);
         }
     
         // Retrieve role name based on role ID
         $role = Roles::find($user->role_id);
         $roleName = $role ? $role->name : null;
     
         $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
     
         // Add the access token and role name to the user object
         $user->access_token = $token;
         $user->role_name = $roleName;
     
         return response()->json(['user' => $user]);
     } 

    public function logout()
    {

        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        dd($user);
    }
    // update user
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'about' => 'required|string',
            'gender' => 'nullable|in:M,F,O',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'required|string',
            'password' => 'sometimes|string'
        ]);
        if ($user->email = $request->email) {


        }
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($user->id);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->username = $request->input('username');
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->about = $request->input('about');
        $user->gender = $request->input('gender');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        $user->save();
        $user = User::where('id', $user->id)->first();
        return response()->json(['message' => 'User updated successfully', 'data' => $user], 200);
    }

    public function add_client(Request $request)
    {
        $role = Roles::where('name', 'client')->first();
        $rules = [
            'username' => 'required|string|unique:users',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'about' => 'required|string',
            'gender' => 'nullable|in:M,F,O',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'username' => $request->input('username'),
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'about' => $request->input('about'),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'role_id' => $role->id,
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json([
                'message' => 'Client added successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    //    delete client

    public function delete_client($id)
    {
        try {
            // Find the client by ID and delete it
            $client = User::findOrFail($id);
            $client->delete();

            return response()->json([
                'message' => 'Client deleted successfully',
                'client' => $client
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    //    update client
    public function update_client(Request $request)
    {
        // Get the authenticated user's ID
        $authUserId = Auth::id();

        $role = Roles::where('name', 'client')->first();

        $rules = [
            'username' => 'required|string|unique:users,username,' . $authUserId,
            'fname' => 'required|string',
            'lname' => 'required|string',
            'about' => 'required|string',
            'gender' => 'nullable|in:M,F,O',
            'email' => 'required|string|email|unique:users,email,' . $authUserId,
            'phone' => 'required|string|max:15|unique:users,phone,' . $authUserId,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::findOrFail($authUserId);

            // Update user details
            $user->username = $request->input('username');
            $user->fname = $request->input('fname');
            $user->lname = $request->input('lname');
            $user->about = $request->input('about');
            $user->gender = $request->input('gender');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->role_id = $role->id;

            $user->save();

            // Update user meta if provided
            if ($request->has('meta')) {
                foreach ($request->input('meta') as $key => $value) {
                    DB::table('users_meta')
                        ->updateOrInsert(
                            ['user_id' => $user->id, 'meta_key' => $key],
                            ['meta_value' => $value]
                        );
                }
            }

            return response()->json([
                'message' => 'Client updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    // get client details

    public function get_client_detail($id)
    {
        try {
            // Find the client by ID
            $client = User::findOrFail($id);

            return response()->json([
                'client' => $client
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

}