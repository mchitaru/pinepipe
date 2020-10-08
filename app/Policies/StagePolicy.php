<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Stage;
use App\User;

class StagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Stage  $stage
     * @return mixed
     */
    public function view(User $user, Stage $stage)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Stage  $stage
     * @return mixed
     */
    public function update(User $user, Stage $stage)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Stage  $stage
     * @return mixed
     */
    public function delete(User $user, Stage $stage)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Stage  $stage
     * @return mixed
     */
    public function restore(User $user, Stage $stage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Stage  $stage
     * @return mixed
     */
    public function forceDelete(User $user, Stage $stage)
    {
        //
    }
}
