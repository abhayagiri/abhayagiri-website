<?php

namespace App\Policies;

use App\Models\Tale;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TalePolicy
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
     * Determine whether the user can view any tales.
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
     * Determine whether the user can view the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tale  $tale
     *
     * @return bool
     */
    public function view(?User $user, tale $tale): bool
    {
        if ($user) {
            return true;
        }
        return $tale->isPublic();
    }

    /**
     * Determine whether the user can create a tale.
     *
     * @param  \App\User  $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tale  $tale
     *
     * @return bool
     */
    public function update(User $user, tale $tale): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tale  $tale
     *
     * @return bool
     */
    public function delete(User $user, tale $tale): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tale  $tale
     *
     * @return bool
     */
    public function restore(User $user, tale $tale): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tale  $tale
     *
     * @return bool
     */
    public function forceDelete(User $user, tale $tale): bool
    {
        return $user->isSuperAdmin();
    }
}
