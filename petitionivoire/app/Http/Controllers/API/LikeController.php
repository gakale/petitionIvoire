<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(Request $request, Petition $petition)
    {
        Auth::user()->likes()->syncWithoutDetaching([$petition->id]);
        return response()->json(['message' => 'Petition liked'], 200);
    }

    public function destroy(Petition $petition)
    {
        Auth::user()->likes()->detach($petition->id);
        return response()->json(['message' => 'Petition unliked'], 200);
    }

    public function index(Petition $petition)
    {
        return response()->json($petition->likes, 200);
    }
}
