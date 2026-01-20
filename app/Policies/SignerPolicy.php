<?php

namespace App\Policies;

use App\Models\Signer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SignerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Signer $signer)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Only director or kaur can manage signers
        return in_array($user->role, ['director', 'kaur']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Signer $signer)
    {
        return in_array($user->role, ['director', 'kaur']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Signer $signer)
    {
        return in_array($user->role, ['director', 'kaur']);
    }
}
