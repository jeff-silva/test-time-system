<?php

namespace App\Policies;

use App\Models\AppUser;
use App\Models\TodoProjectTask;
use Illuminate\Auth\Access\Response;

class TodoProjectTaskPolicy
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
    public function view(AppUser $appUser, TodoProjectTask $todoProjectTask): bool
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
    public function update(AppUser $appUser, TodoProjectTask $todoProjectTask): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(AppUser $appUser, TodoProjectTask $todoProjectTask): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(AppUser $appUser, TodoProjectTask $todoProjectTask): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(AppUser $appUser, TodoProjectTask $todoProjectTask): bool
    {
        return true;
    }
}
