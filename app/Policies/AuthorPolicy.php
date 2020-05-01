<?php

namespace App\Policies;

use App\Models\Author;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
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
     * Determine whether the user can view any authors.
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
     * Determine whether the user can view the author.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Author  $author
     *
     * @return bool
     */
    public function view(?User $user, Author $author): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a author.
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
     * Determine whether the user can update the author.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Author  $author
     *
     * @return bool
     */
    public function update(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the author.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Author  $author
     *
     * @return bool
     */
    public function delete(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the author.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Author  $author
     *
     * @return bool
     */
    public function restore(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the author.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Author  $author
     *
     * @return bool
     */
    public function forceDelete(User $user, Author $author): bool
    {
        return $user->isSuperAdmin();
    }
}
