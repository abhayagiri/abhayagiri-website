<?php

namespace App\Policies;

use App\Models\Subpage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubpagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any subpages.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the subpage.
     *
     * @param  \App\User  $user
     * @param  \App\Subpage  $subpage
     *
     * @return mixed
     */
    public function view(?User $user, Subpage $subpage)
    {
        if ($user) {
            return true;
        }
        return $subpage->isPublic();
    }

    /**
     * Determine whether the user can create subpages.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the subpage.
     *
     * @param  \App\User  $user
     * @param  \App\Subpage  $subpage
     *
     * @return mixed
     */
    public function update(User $user, Subpage $subpage)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the subpage.
     *
     * @param  \App\User  $user
     * @param  \App\Subpage  $subpage
     *
     * @return mixed
     */
    public function delete(User $user, Subpage $subpage)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the subpage.
     *
     * @param  \App\User  $user
     * @param  \App\Subpage  $subpage
     *
     * @return mixed
     */
    public function restore(User $user, Subpage $subpage)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the subpage.
     *
     * @param  \App\User  $user
     * @param  \App\Subpage  $subpage
     *
     * @return mixed
     */
    public function forceDelete(User $user, Subpage $subpage)
    {
        return $user->isSuperAdmin();
    }
}
