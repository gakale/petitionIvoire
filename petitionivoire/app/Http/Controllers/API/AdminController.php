<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    public function dashboardStats()
    {
        $approvedCount = Petition::where('status', 'approved')->count();
        $pendingCount = Petition::where('status', 'pending')->count();
        $ongoingCount = Petition::where('status', 'ongoing')->count();

        return response()->json([
            'approved_count' => $approvedCount,
            'pending_count' => $pendingCount,
            'ongoing_count' => $ongoingCount,
        ]);
    }

    public function approvePetition(Petition $petition)
    {
        $petition->update(['status' => 'approved']);
        return response()->json(['message' => 'Petition approved successfully']);
    }

    public function rejectPetition(Petition $petition)
    {
        $petition->update(['status' => 'rejected']);
        return response()->json(['message' => 'Petition rejected successfully']);
    }

    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|unique:categories|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json($category);
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }

    public function reportedComments()
    {
        $reports = Report::with('comment', 'user')->get();
        return response()->json($reports);
    }

    public function resolveReportedComment(Request $request, Comment $comment)
    {
        $deleteComment = $request->input('delete', false);

        if ($deleteComment) {
            $comment->delete();
        }

        $reports = Report::where('comment_id', $comment->id)->update(['resolved' => true]);

        return response()->json(['message' => 'Report resolved successfully']);
    }

}
