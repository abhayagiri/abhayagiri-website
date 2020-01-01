<?php

namespace App\Policies;

use App\Models\Reflection;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReflectionPolicy
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
     * Determine whether the user can view any reflections.
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
     * Determine whether the user can view the reflection.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reflection  $reflection
     *
     * @return bool
     */
    public function view(?User $user, Reflection $reflection): bool
    {
        if ($user) {
            return true;
        }
        return $reflection->isPublic();
    }

    /**
     * Determine whether the user can create a reflection.
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
     * Determine whether the user can update the reflection.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reflection  $reflection
     *
     * @return bool
     */
    public function update(User $user, Reflection $reflection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the reflection.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reflection  $reflection
     *
     * @return bool
     */
    public function delete(User $user, Reflection $reflection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the reflection.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reflection  $reflection
     *
     * @return bool
     */
    public function restore(User $user, Reflection $reflection): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the reflection.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Reflection  $reflection
     *
     * @return bool
     */
    public function forceDelete(User $user, Reflection $reflection): bool
    {
        return $user->isSuperAdmin();
    }
}
