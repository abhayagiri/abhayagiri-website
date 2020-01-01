<?php

namespace App\Policies;

use App\Models\News;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
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
     * Determine whether the user can view any news articles.
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
     * Determine whether the user can view the news article.
     *
     * @param  \App\User  $user
     * @param  \App\Models\News  $news
     *
     * @return bool
     */
    public function view(?User $user, News $news): bool
    {
        if ($user) {
            return true;
        }
        return $news->isPublic();
    }

    /**
     * Determine whether the user can create a news article.
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
     * Determine whether the user can update the news article.
     *
     * @param  \App\User  $user
     * @param  \App\Models\News  $news
     *
     * @return bool
     */
    public function update(User $user, News $news): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the news article.
     *
     * @param  \App\User  $user
     * @param  \App\Models\News  $news
     *
     * @return bool
     */
    public function delete(User $user, News $news): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the news article.
     *
     * @param  \App\User  $user
     * @param  \App\Models\News  $news
     *
     * @return bool
     */
    public function restore(User $user, News $news): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the news article.
     *
     * @param  \App\User  $user
     * @param  \App\Models\News  $news
     *
     * @return bool
     */
    public function forceDelete(User $user, News $news): bool
    {
        return $user->isSuperAdmin();
    }
}
