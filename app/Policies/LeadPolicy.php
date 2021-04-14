<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Lead;
use App\User;

class LeadPolicy
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
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function view(User $user, Lead $lead)
    {
        return $lead->user_id == $user->id ||
                $lead->created_by == $user->id;
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
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function update(User $user, Lead $lead)
    {
        return $lead->user_id == $user->id ||
                $lead->created_by == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function delete(User $user, Lead $lead)
    {
        return $lead->user_id == $user->id ||
                $lead->created_by == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function restore(User $user, Lead $lead)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Lead  $lead
     * @return mixed
     */
    public function forceDelete(User $user, Lead $lead)
    {
        //
    }
}
