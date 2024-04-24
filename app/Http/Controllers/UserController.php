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
        $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
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

            if ($request->has('meta')) {
                foreach ($request->input('meta') as $key => $value) {
                    DB::table('users_meta')->insert([
                        'user_id' => $user->id,
                        'meta_key' => $key,
                        'meta_value' => $value,
                    ]);
                }
            }
            return response()->json([
                'message' => 'Client added successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function update_client(Request $request)
    {
        // Get the authenticated user's ID
        $authUserId = Auth::id();

        $role = Roles::where('name', 'client')->first();

        // Modify validation rules to exclude the current user's username and email
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

            $user->update([
                'username' => $request->input('username'),
                'fname' => $request->input('fname'),
                'lname' => $request->input('lname'),
                'about' => $request->input('about'),
                'gender' => $request->input('gender'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'role_id' => $role->id,
            ]);

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

// get_all_clients
    public function get_all_clients()
    {
        $clients = User::where('role_id', 2)->get();
        return response()->json([
            'clients' => $clients
        ], 200);
    }

    // get_client by id

    public function get_client($id)
    {
        $client = User::where('id', $id)->first();
        return response()->json([
            'client' => $client
        ], 200);
    }

// get client details
public function get_client_detail(Request $request)
{
    try {
        // Get the authenticated user
        $user = Auth::user();

        // Load the meta data associated with the user
        $user->load('meta');

        // Return the user details along with meta information
        return response()->json([
            'user' => $user
        ], 200);
    } catch (\Exception $e) {
        // Return error response if an exception occurs
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}




}
