<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Checklist;
use App\User;
use App\Task;

class ChecklistPolicy
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
     * @param  \App\Checklist  $checklist
     * @return mixed
     */
    public function view(User $user, Checklist $checklist)
    {
        return $user->can('view', $checklist->checklistable);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $checklistable)
    {
        return $user->can('update', [$checklistable, true]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Checklist  $checklist
     * @return mixed
     */
    public function update(User $user, Checklist $checklist)
    {
        return $user->can('update', [$checklist->checklistable, true]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Checklist  $checklist
     * @return mixed
     */
    public function delete(User $user, Checklist $checklist)
    {
        return $checklist->created_by == $user->id  ||
                $user->can('update', [$checklist->checklistable, true]);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Checklist  $checklist
     * @return mixed
     */
    public function restore(User $user, Checklist $checklist)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Checklist  $checklist
     * @return mixed
     */
    public function forceDelete(User $user, Checklist $checklist)
    {
        //
    }
}
