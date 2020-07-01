<?php

namespace App\Policies;

use App\Models\Album;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbumPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any albums.
     *
     * @param  \App\User  $user
     *
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     *
     * @return bool
     */
    public function view(?User $user, Album $album): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a album.
     *
     * @param  \App\User  $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     *
     * @return bool
     */
    public function update(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     *
     * @return bool
     */
    public function delete(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     *
     * @return bool
     */
    public function restore(User $user, Album $album): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the album.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Album  $album
     *
     * @return bool
     */
    public function forceDelete(User $user, Album $album): bool
    {
        return false;
    }
}
