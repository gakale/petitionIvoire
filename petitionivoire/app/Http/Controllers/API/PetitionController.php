<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use Illuminate\Http\Request;
use App\Models\Signature;

class PetitionController extends Controller
{
    public function index()
    {
        $petitions = Petition::with('category')->get();
        return response()->json($petitions);
    }

    public function show(Petition $petition)
    {
        $petition->load('category');
        return response()->json($petition);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal' => 'required|integer|min:1',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'nullable|image',
        ]);

        $petition = new Petition($validatedData);
        $petition->user_id = $request->user()->id;
        $petition->save();

        return response()->json($petition, 201);
    }

    public function update(Request $request, Petition $petition)
    {
        $this->authorize('update', $petition);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'goal' => 'sometimes|required|integer|min:1',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'image' => 'nullable|image',
        ]);

        $petition->update($validatedData);

        return response()->json($petition);
    }

    public function destroy(Petition $petition)
    {
        $this->authorize('delete', $petition);
        $petition->delete();

        return response()->json(['message' => 'Petition deleted successfully'], 200);
    }

    public function sign(Request $request, Petition $petition)
    {
        $user = $request->user();

        // Vérifie si l'utilisateur a déjà signé la pétition
        $hasSigned = $petition->signatures()->where('user_id', $user->id)->exists();
        if ($hasSigned) {
            return response()->json(['message' => 'You have already signed this petition'], 400);
        }

        // Ajoute la signature de l'utilisateur à la pétition
        $petition->signatures()->create(['user_id' => $user->id]);

        // Met à jour le nombre de signatures de la pétition
        $petition->increment('signatures_count');

        // Vérifie si l'objectif de la pétition a été atteint
        if ($petition->goal && $petition->signatures_count >= $petition->goal) {
            // Modifiez le statut de la pétition pour indiquer que l'objectif a été atteint
            $petition->update(['status' => 'goal_reached']);
        }

        return response()->json(['message' => 'Thank you for signing the petition']);
    }

    public function getPetitionsByCategory(Request $request, int $category_id)
    {
        $petitions = Petition::where('category_id', $category_id)->with('category')->get();
        return response()->json($petitions);
    }


    public function reachedGoals()
    {
        $petitions = Petition::whereColumn('signatures', '>=', 'goal')->get();
        return response()->json($petitions);
    }

    public function userPetitions(Request $request)
    {
        $user = $request->user();
        $petitions = $user->petitions;
        return response()->json($petitions);
    }

    public function userUnapprovedPetitions(Request $request)
    {
        $user = $request->user();
        $petitions = $user->petitions()->where('status', '!=', 'approved')->get();
        return response()->json($petitions);
    }


}

