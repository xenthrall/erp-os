<?php

namespace App\Policies\ER;

use App\Models\ER\ErType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ErTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('er.types.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ErType $erType): bool
    {
        return $user->can('er.types.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('er.types.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ErType $erType): bool
    {
        return $user->can('er.types.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ErType $erType): bool
    {
        return $user->can('er.types.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ErType $erType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ErType $erType): bool
    {
        return false;
    }
}
