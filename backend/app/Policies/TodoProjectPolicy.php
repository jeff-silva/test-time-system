<?php

namespace App\Policies;

use App\Models\AppUser;
use App\Models\TodoProject;
use Illuminate\Auth\Access\Response;

class TodoProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(AppUser $appUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(AppUser $appUser, TodoProject $todoProject): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(AppUser $appUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(AppUser $appUser, TodoProject $todoProject): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(AppUser $appUser, TodoProject $todoProject): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AppUser $appUser, TodoProject $todoProject): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(AppUser $appUser, TodoProject $todoProject): bool
    {
        return true;
    }
}
