<?php

namespace App\Policies;

use App\Models\Citation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Citation $citation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Citation $citation): bool
    {
        if($user->role === 'admin'){
            return true;
        }
        return $user->id === $citation->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Citation $citation): bool
    {
        if($user->role === 'admin'){
            return true;
        }
        return $user->id === $citation->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Citation $citation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Citation $citation): bool
    {
        return false;
    }
}
