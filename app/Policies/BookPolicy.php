<?php

namespace App\Policies;

use App\Models\Book;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
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
     * Determine whether the user can view any books.
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
     * Determine whether the user can view the book.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Book  $book
     *
     * @return bool
     */
    public function view(?User $user, Book $book): bool
    {
        if ($user) {
            return true;
        }
        return $book->isPublic();
    }

    /**
     * Determine whether the user can create a book.
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
     * Determine whether the user can update the book.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Book  $book
     *
     * @return bool
     */
    public function update(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the book.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Book  $book
     *
     * @return bool
     */
    public function delete(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the book.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Book  $book
     *
     * @return bool
     */
    public function restore(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the book.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Book  $book
     *
     * @return bool
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return $user->isSuperAdmin();
    }
}
