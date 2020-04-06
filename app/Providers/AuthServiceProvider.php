<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\News;
use App\Models\Reflection;
use App\Models\Subpage;
use App\Models\Tale;
use App\Policies\BookPolicy;
use App\Policies\NewsPolicy;
use App\Policies\ReflectionPolicy;
use App\Policies\SubpagePolicy;
use App\Policies\TalePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Book::class => BookPolicy::class,
        News::class => NewsPolicy::class,
        Reflection::class => ReflectionPolicy::class,
        Subpage::class => SubpagePolicy::class,
        Tale::class => TalePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
