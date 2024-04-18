<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;

class MainController extends Controller
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
   
     public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->username.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
    
        return response()->json([
          "message"=>"logged out"
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        dd($user);
    }

}
