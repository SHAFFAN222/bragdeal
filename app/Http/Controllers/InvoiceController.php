<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
class InvoiceController extends Controller
{
    public function get()
    
    {  
        
        $invoices  = invoice::get();
       
      
        return response()->json(['message' => 'Get  invoice Successfully','data' => $invoices ], 200);

    }   
    public function getById($id)
    
    {    
        //   var_dump('invoices');die();
      $invoice = invoice::where('id',$id)->first();  
      return response()->json(['message' => 'Get id Successfully','data' => $invoice], 200);
    }
    public function add(Request $request) {
      
        $rules = [
            'client_id' =>'required|string',
            'project_id' =>'required|string',
            'price' =>'required|string',
           
           
        
         
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $invoice = new invoice;
        $invoice->client_id = $request->client_id;
        $invoice->project_id = $request->project_id;
        $invoice->price = $request->price;
        
   
      
        $invoice->save();
        return response()->json(['message' => 'Add invoice Successfully','data' => $invoice], 200);
    
    }
    public function update(Request $request)
{
    // Check if ID is provided
    if (!$request->has('id')) {
        return response()->json(['error' => 'ID not provided'], 422);
    }

    $invoice = Invoice::find($request->id);
    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    $rules = [
        'client_id' => 'required|string',
        'project_id' => 'required|string',
        'price' => 'required|string',
    ];
    
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if ($request->has('client_id')) {
        $invoice->client_id = $request->input('client_id');
    }
    if ($request->has('project_id')) {
        $invoice->project_id = $request->input('project_id');
    }
    if ($request->has('price')) {
        $invoice->price = $request->input('price');
    }
   
    $invoice->save();
    return response()->json(['message' => 'Update invoice successfully', 'data' => $invoice], 200);
}

    public function delete( $id)
{
    $invoice = Invoice::find($id);
   
    if (!$invoice) {
        return response()->json(['message' => 'Invoice not found'], 404);
    }

    $invoice->delete();

    return response()->json(['message' => 'Invoice deleted successfully'], 200);
}

}