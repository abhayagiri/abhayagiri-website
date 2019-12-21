<?php

namespace App\Providers;

use App\Models\News;
use App\Models\Reflection;
use App\Models\Subpage;
use App\Policies\NewsPolicy;
use App\Policies\ReflectionPolicy;
use App\Policies\SubpagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        News::class => NewsPolicy::class,
        Reflection::class => ReflectionPolicy::class,
        Subpage::class => SubpagePolicy::class,
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
