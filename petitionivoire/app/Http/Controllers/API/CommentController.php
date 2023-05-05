<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Petition;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

    public function index($type, $id)
    {
        $commentable = $this->getCommentable($type, $id);

        if (!$commentable) {
            return response()->json(['error' => 'Invalid type or ID'], 404);
        }

        return response()->json($commentable->comments, 200);
    }

    public function store(Request $request, $type, $id)
    {
        $commentable = $this->getCommentable($type, $id);

        if (!$commentable) {
            return response()->json(['error' => 'Invalid type or ID'], 404);
        }

        $request->validate([
            'body' => 'required|max:500',
        ]);

        $comment = new Comment([
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);

        $commentable->comments()->save($comment);

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

    private function getCommentable($type, $id)
    {
        $commentable = null;

        switch ($type) {
            case 'petitions':
                $commentable = Petition::find($id);
                break;
            case 'topics':
                $commentable = Topic::find($id);
                break;
        }

        return $commentable;
    }
}
