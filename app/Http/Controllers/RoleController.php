<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roles; // Import the Roles model
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Retrieve all roles from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function roles(Request $request)
    {
        // Retrieve all roles from the database
        $roles = Roles::all();

        // Return roles as a JSON response
        return response()->json([
            'roles' => $roles
        ]);
    }
}
