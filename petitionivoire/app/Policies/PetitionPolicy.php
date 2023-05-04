<?php

namespace App\Policies;

use App\Models\Petition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetitionPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Petition $petition)
    {
        return $user->id === $petition->user_id && !$petition->approved;
    }

    public function delete(User $user, Petition $petition)
    {
        return $user->id === $petition->user_id && !$petition->approved;
    }
}
