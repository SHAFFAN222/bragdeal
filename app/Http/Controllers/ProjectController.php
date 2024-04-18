<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
         
        ]);

        Project::create([
            'name' => $request->name,
            
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }
}