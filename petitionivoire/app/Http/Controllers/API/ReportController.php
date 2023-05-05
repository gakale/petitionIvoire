<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store']);
        $this->middleware('role:admin')->only(['index']);
    }

    public function index()
    {
        $reports = Report::with('comment', 'user')->get();
        return response()->json($reports, 200);
    }

    public function store(Request $request, Comment $comment)
    {
        $request->validate([
            'reason' => 'required|max:255',
        ]);

        $report = new Report([
            'user_id' => auth()->id(),
            'comment_id' => $comment->id,
            'reason' => $request->reason,
        ]);

        $report->save();
        return response()->json($report, 201);
    }

    public function resolve(Comment $comment)
    {
        $comment->reports()->delete();
        return response()->json(['message' => 'Reports resolved'], 200);
    }
}
