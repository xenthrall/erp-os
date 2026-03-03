<?php

namespace App\Policies\ER;

use App\Models\ER\ErReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ErReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('er.reports.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ErReport $erReport): bool
    {
        return $user->can('er.reports.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('er.reports.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ErReport $erReport): bool
    {
        return $user->can('er.reports.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ErReport $erReport): bool
    {
        return $user->can('er.reports.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ErReport $erReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ErReport $erReport): bool
    {
        return false;
    }
}
