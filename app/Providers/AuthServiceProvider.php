<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Author;
use App\Models\Book;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Resident;
use App\Models\Subpage;
use App\Models\Tale;
use App\Models\Talk;
use App\Policies\AlbumPolicy;
use App\Policies\AuthorPolicy;
use App\Policies\BookPolicy;
use App\Policies\NewsPolicy;
use App\Policies\ReflectionPolicy;
use App\Policies\ResidentPolicy;
use App\Policies\SubpagePolicy;
use App\Policies\TalePolicy;
use App\Policies\TalkPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Album::class => AlbumPolicy::class,
        Author::class => AuthorPolicy::class,
        Book::class => BookPolicy::class,
        News::class => NewsPolicy::class,
        Reflection::class => ReflectionPolicy::class,
        Resident::class => ResidentPolicy::class,
        Subpage::class => SubpagePolicy::class,
        Tale::class => TalePolicy::class,
        Talk::class => TalkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
