<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Petition;
use App\Models\Comment;

class LikeController extends Controller
{
    public function store(Request $request, $type, $id)
    {
        $likeable = $this->getLikeable($type, $id);

        if (!$likeable) {
            return response()->json(['error' => 'Invalid type or ID'], 404);
        }

        $existingLike = $likeable->likes()->where('user_id', $request->user()->id)->first();

        if ($existingLike) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        $like = new Like(['user_id' => $request->user()->id]);
        $likeable->likes()->save($like);

        return response()->json(['message' => 'Like added'], 201);
    }

    public function destroy(Request $request, $type, $id)
    {
        $likeable = $this->getLikeable($type, $id);

        if (!$likeable) {
            return response()->json(['error' => 'Invalid type or ID'], 404);
        }

        $existingLike = $likeable->likes()->where('user_id', $request->user()->id)->first();

        if (!$existingLike) {
            return response()->json(['message' => 'Like not found'], 404);
        }

        $existingLike->delete();

        return response()->json(['message' => 'Like removed'], 200);
    }

    public function index(Request $request, $type, $id)
    {
        $likeable = $this->getLikeable($type, $id);

        if (!$likeable) {
            return response()->json(['error' => 'Invalid type or ID'], 404);
        }

        $likes = $likeable->likes;

        return response()->json($likes);
    }

    private function getLikeable($type, $id)
    {
        $likeable = null;

        switch ($type) {
            case 'petitions':
                $likeable = Petition::find($id);
                break;
            case 'comments':
                $likeable = Comment::find($id);
                break;
        }

        return $likeable;
    }
}
