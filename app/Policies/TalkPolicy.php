<?php

namespace App\Policies;

use App\Models\Talk;
use App\User;
use Illuminate\Auth\Access\HandlesAuthoization;

class TalkPolicy
{
    use HandlesAuthoization;

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
     * Determine whether the user can view any talks.
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
     * Determine whether the user can view the talk.
     *
     * @param  \App\User  $user
     * @param  \App\Models\talk  $talk
     *
     * @return bool
     */
    public function view(?User $user, talk $talk): bool
    {
        if ($user) {
            return true;
        }
        return $talk->isPublic();
    }

    /**
     * Determine whether the user can create a talk.
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
     * Determine whether the user can update the talk.
     *
     * @param  \App\User  $user
     * @param  \App\Models\talk  $talk
     *
     * @return bool
     */
    public function update(User $user, talk $talk): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the talk.
     *
     * @param  \App\User  $user
     * @param  \App\Models\talk  $talk
     *
     * @return bool
     */
    public function delete(User $user, talk $talk): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the talk.
     *
     * @param  \App\User  $user
     * @param  \App\Models\talk  $talk
     *
     * @return bool
     */
    public function restore(User $user, talk $talk): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the talk.
     *
     * @param  \App\User  $user
     * @param  \App\Models\talk  $talk
     *
     * @return bool
     */
    public function forceDelete(User $user, talk $talk): bool
    {
        return $user->isSuperAdmin();
    }
}
