<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Media;
use App\User;

class MediaPolicy
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
     * @param  \App\Media  $media
     * @return mixed
     */
    public function view(User $user, Media $media)
    {
        return $user->can('view', $media->model);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $model)
    {
        return $user->can('view', [$model, true]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function update(User $user, Media $media)
    {
        return $media->user_id == $user->id ||
                $media->created_by == $user->id  ||
                $user->can('update', [$media->model, false]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function delete(User $user, Media $media)
    {
        return $media->user_id == $user->id ||
                $media->created_by == $user->id  ||
                $user->can('update', [$media->model, false]);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function restore(User $user, Media $media)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Media  $media
     * @return mixed
     */
    public function forceDelete(User $user, Media $media)
    {
        //
    }
}
