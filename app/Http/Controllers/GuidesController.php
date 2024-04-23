<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuidesController extends Controller
{
   
    public function get_guides()
    
    {    
        $guides  = Guide::get();
        $guides ->transform(function ($guides ) {
            $guides ->attachment = $guides ->attachment_url;
                    return $guides;
           });
        return response()->json(['message' => 'Get  Guide Successfully','data' => $guides ], 200);

    }
    
    public function getById($id)
    
    {    
      $guide = Guide::where('id',$id)->first();  
      return response()->json(['message' => 'Get id Successfully','data' => $guide], 200);
    }


    public function delete($id)
    {
        $guide = Guide::find($id);
        if (!$guide) {
            return response()->json(['message' => 'Guide not found'], 404);
        }

        $guide->delete();

        return response()->json(['message' => 'Guide deleted successfully'], 200);
    }
}