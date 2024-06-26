<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\CompanySettings;
use App\User;

class CompanySettingsPolicy
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
     * @param  \App\CompanySettings  $companySettings
     * @return mixed
     */
    public function view(User $user, CompanySettings $companySettings)
    {
        return $companySettings->created_by == $user->id;
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
     * @param  \App\CompanySettings  $companySettings
     * @return mixed
     */
    public function update(User $user, CompanySettings $companySettings)
    {
        return $companySettings->created_by == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\CompanySettings  $companySettings
     * @return mixed
     */
    public function delete(User $user, CompanySettings $companySettings)
    {
        return $companySettings->created_by == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\CompanySettings  $companySettings
     * @return mixed
     */
    public function restore(User $user, CompanySettings $companySettings)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\CompanySettings  $companySettings
     * @return mixed
     */
    public function forceDelete(User $user, CompanySettings $companySettings)
    {
        //
    }
}
