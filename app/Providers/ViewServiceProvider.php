<?php

namespace App\Providers;

use App\Http\View\Pages;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('app.article-links', function ($view) {
            $view->with('page', app('pages')->current());
        });
        View::composer('app.back-to', function ($view) {
            $view->with('page', app('pages')->current());
        });
        View::composer('app.header', function ($view) {
            $view->with('page', app('pages')->current())
                 ->with('pages', app('pages')->all())
                 ->with('otherLngData', app('pages')->otherLngData());
        });
        View::composer('app.index-title', function ($view) {
            $view->with('page', app('pages')->current());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('pages', function () {
            return new Pages();
        });
    }
}
