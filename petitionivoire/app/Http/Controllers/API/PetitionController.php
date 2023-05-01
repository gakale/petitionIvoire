<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetitionController extends Controller
{
    public function __construct()
    {
        // Middleware pour s'assurer que seuls les utilisateurs authentifiés peuvent créer, mettre à jour et supprimer des pétitions
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        $petitions = Petition::where('status', 'accepted')->get(); // Ne montrer que les pétitions acceptées
        return response()->json($petitions, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'signature_goal' => 'required|integer|min:1',
            'image_url' => 'nullable|url',
        ]);

        $petition = new Petition([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'signature_goal' => $request->signature_goal,
            'image_url' => $request->image_url,
            'user_id' => Auth::id(),
        ]);

        $petition->save();

        return response()->json($petition, 201);
    }

    public function show(Petition $petition)
    {
        if ($petition->status != 'accepted') {
            return response()->json(['error' => 'Petition not found'], 404);
        }

        return response()->json($petition, 200);
    }

    public function update(Request $request, Petition $petition)
    {
        // Vérifier si l'utilisateur est l'auteur de la pétition et si la pétition n'a pas encore été validée
        if ($petition->user_id != Auth::id() || $petition->status != 'pending') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'signature_goal' => 'required|integer|min:1',
            'image_url' => 'nullable|url',
        ]);

        $petition->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'signature_goal' => $request->signature_goal,
            'image_url' => $request->image_url,
        ]);

        return response()->json($petition, 200);
    }

    public function destroy(Petition $petition)
    {
        // Vérifier si l'utilisateur est l'auteur de la pétition et si la pétition n'a pas encore été validée
        if ($petition->user_id != Auth::id() || $petition->status != 'pending') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $petition->delete();
        return response()->json(['message' => 'Petition deleted'], 200);
    }
}
