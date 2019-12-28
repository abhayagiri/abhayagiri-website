<?php

namespace App\Providers;

use App\Http\View\Breadcrumbs;
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
        Blade::directive('breadcrumb', function ($expression) {
            return "<?php \\Breadcrumbs::addBreadcrumb(${expression}); ?>";
        });
        Blade::directive('breadcrumb', function ($expression) {
            return "<?php \\Breadcrumbs::addBreadcrumb(${expression}); ?>";
        });
        View::composer('app.back-to', function ($view) {
            $view->with('page', app('pages')->current());
        });
        View::composer('app.banner', function ($view) {
            $view->with('pageSlug', app('pages')->slug());
        });
        View::composer([
            'app.breadcrumbs',
            'app.index-breadcrumb-title',
        ], function ($view) {
            $view->with('breadcrumbs', app('breadcrumbs'));
        });
        View::composer('app.language', function ($view) {
            $view->with('otherLngData', app('pages')->otherLngData());
        });
        View::composer('app.nav-menu', function ($view) {
            $view->with('pages', app('pages')->all());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('breadcrumbs', function () {
            return new Breadcrumbs();
        });
        $this->app->singleton('pages', function () {
            return new Pages();
        });
    }
}
