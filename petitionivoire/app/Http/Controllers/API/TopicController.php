<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::all();
        return response()->json($topics);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $topic = Topic::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json($topic, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $topic->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json($topic);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return response()->json(['message' => 'Topic deleted'], 200);
    }

    public function popularTopics()
    {
        $topics = Topic::withCount('likes')->orderBy('likes_count', 'desc')->get();
        return response()->json($topics);
    }

}
