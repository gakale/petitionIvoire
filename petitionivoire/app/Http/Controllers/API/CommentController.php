<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Petition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

    public function index(Petition $petition)
    {
        return response()->json($petition->comments, 200);
    }

    public function store(Request $request, Petition $petition)
    {
        $request->validate([
            'body' => 'required|max:500',
        ]);

        $comment = new Comment([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'petition_id' => $petition->id,
        ]);

        $comment->save();
        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }


        $request->validate([
            'body' => 'required|max:500',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return response()->json($comment, 200);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted'], 200);
    }
}
