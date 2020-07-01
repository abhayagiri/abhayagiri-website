<?php

namespace App\Policies;

use App\Models\Resident;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResidentPolicy
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
     * Determine whether the user can view any residents.
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
     * Determine whether the user can view the resident.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Resident  $resident
     *
     * @return bool
     */
    public function view(?User $user, Resident $resident): bool
    {
        if ($user) {
            return true;
        }
        return $resident->isPublic();
    }

    /**
     * Determine whether the user can create a resident.
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
     * Determine whether the user can update the resident.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Resident  $resident
     *
     * @return bool
     */
    public function update(User $user, Resident $resident): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the resident.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Resident  $resident
     *
     * @return bool
     */
    public function delete(User $user, Resident $resident): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the resident.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Resident  $resident
     *
     * @return bool
     */
    public function restore(User $user, Resident $resident): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the resident.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Resident  $resident
     *
     * @return bool
     */
    public function forceDelete(User $user, Resident $resident): bool
    {
        return $user->isSuperAdmin();
    }
}
