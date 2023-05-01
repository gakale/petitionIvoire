<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
        $this->middleware('role:admin')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $category = new Category([
            'name' => $request->name,
        ]);

        $category->save();
        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json($category, 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted'], 200);
    }
}
