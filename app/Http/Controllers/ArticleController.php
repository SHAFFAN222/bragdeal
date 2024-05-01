<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function get()
    {  
        $articles = Article::get();
        $articles->transform(function ($article) { 
            $article->image = $article->image_url;
            return $article; 
        });
        return response()->json(['message' => 'Get articles successfully','data' => $articles], 200);
    }
    
    public function getById($id)
    {   
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        return response()->json(['message' => 'Get article by ID successfully','data' => $article], 200);
    }

    public function add(Request $request)
    {
        $user = Auth::user(); 

        $rules = [
            'article' => 'required|string',
            'start_date' => 'required|date',
            'external_url' => 'required|string',
            'like_count' => 'required|integer',
            'comment' => 'required|string',
            'image' => 'nullable|file|mimes:jpg,gif,jpeg,png,doc,xls,docx,xlsx,pdf|max:2048',
            'status' => 'nullable|in:draft,published,archived',
            'editorValue' => 'required|string',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new article instance
        $article = new Article();

        $article->article = $request->input('article');
        $article->start_date = $request->input('start_date');
        $article->external_url = $request->input('external_url');
        $article->like_count = $request->input('like_count');
        $article->comment = $request->input('comment');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images');
            $article->image = $imagePath;
        }

        $article->author_id = $user->id;
        $article->status = $request->input('status');
        $article->editorValue = $request->input('editorValue');
        
        $article->save();
        return response()->json(['message' => 'Article created successfully', 'article' => $article], 200);
    }   

    public function update(Request $request)
    {
        $article = Article::find($request->id);
        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        $rules = [
            'article' => 'required|string',
            'start_date' => 'required|date',
            'external_url' => 'required|string',
            'like_count' => 'required|integer',
            'comment' => 'required|string',
            'image' => 'nullable|file|mimes:jpg,gif,jpeg,png,doc,xls,docx,xlsx,pdf|max:2048',
            'status' => 'required|nullable|in:draft,published,archived',
            'editorValue' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $article->article = $request->input('article');
        $article->start_date = $request->input('start_date');
        $article->external_url = $request->input('external_url');
        $article->like_count = $request->input('like_count');
        $article->comment = $request->input('comment');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images');
            $article->image = $imagePath;
        }

        $article->status = $request->input('status');
        $article->editorValue = $request->input('editorValue');
        
        $article->save();
        return response()->json(['message' => 'Article updated successfully', 'data' => $article], 200);
    }
    
    public function delete($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();
        return response()->json(['message' => 'Article deleted successfully', 'data' => $article], 200);
    }
}