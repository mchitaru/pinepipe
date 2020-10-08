<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Timesheet;
use App\User;

class TimesheetPolicy
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
     * @param  \App\Timesheet  $timesheet
     * @return mixed
     */
    public function view(User $user, Timesheet $timesheet)
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
     * @param  \App\Timesheet  $timesheet
     * @return mixed
     */
    public function update(User $user, Timesheet $timesheet)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timesheet  $timesheet
     * @return mixed
     */
    public function delete(User $user, Timesheet $timesheet)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timesheet  $timesheet
     * @return mixed
     */
    public function restore(User $user, Timesheet $timesheet)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Timesheet  $timesheet
     * @return mixed
     */
    public function forceDelete(User $user, Timesheet $timesheet)
    {
        //
    }
}
