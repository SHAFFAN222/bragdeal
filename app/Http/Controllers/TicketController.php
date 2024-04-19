<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{


    //   add tickets

    public function add_tickets(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,gif,jpeg,png,doc,xls,docx,xlsx,pdf|max:2048', // File validation rules
            'subject' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'priority' => 'required|string|max:50',
        ]);

        // Create a new Ticket instance and assign the validated data
        $ticket = new Ticket([
            'title' => $validatedData['title'],
            'message' => $validatedData['message'],
            'attachment' => $validatedData['attachment'] ? $validatedData['attachment']->store('attachments', 'public') : null,
            'subject' => $validatedData['subject'],
            'department' => $validatedData['department'],
            'priority' => $validatedData['priority'],
        ]);

        // Associate the ticket with the user by setting the user_id
        $ticket->user_id = $user->id;

        // Save the new ticket instance
        $ticket->save();

        // Return a JSON response indicating success and the new ticket data
        return response()->json([
            'message' => 'Ticket added successfully',
            'data' => $ticket
        ], 200);
    }

    //update a ticket
    public function update_ticket(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Find the ticket that belongs to the authenticated user
        $ticket = Ticket::where('user_id', $user->id)->first();

        // If no ticket is found, return a 404 response
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        // Validation rules for updating the ticket
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file',
            'subject' => 'required|string',
            'department' => 'required|string',
            'priority' => 'required|string|',
            // Add other fields and validation rules here
        ]);

        // If validation fails, return validation errors as a JSON response
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the ticket fields based on request input
        if ($request->has('title')) {
            $ticket->title = $request->input('title');
        }
        if ($request->has('message')) {
            $ticket->message = $request->input('message');
        }
        if ($request->has('attachment')) {
            $ticket->attachment = $request->file('attachment')->store('attachments');
        }
        if ($request->has('subject')) {
            $ticket->subject = $request->input('subject');
        }
        if ($request->has('department')) {
            $ticket->department = $request->input('department');
        }
        if ($request->has('priority')) {
            $ticket->priority = $request->input('priority');
        }

        // Save the updated ticket to the database
        $ticket->save();

        // Return the updated ticket as a JSON response
        return response()->json([
            'message' => 'Ticket updated successfully',
            'data' => $ticket
        ], 200);
    }

    // get by user id 
    public function get_user_ticket()
    {
        $user = Auth::user();

        $tickets = Ticket::where('user_id', $user->id)->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No tickets found for the specified user ID'], 404);
        }

        return response()->json([
            'message' => 'Tickets retrieved successfully',
            'data' => $tickets,
        ], 200);
    }

    // get by ticket id
    public function get_ticket($id){
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        return response()->json([
            'message' => 'Ticket retrieved successfully',
            'data' => $ticket,
        ], 200);
    }

    // get all tickets
    public function get_all_tickets(){
        $tickets = Ticket::all();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No tickets found'], 404);
        }

        return response()->json([
            'message' => 'Tickets retrieved successfully',
            'data' => $tickets,
        ], 200);
    }
 
}
