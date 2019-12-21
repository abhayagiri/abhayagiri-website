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
    public function boot(Breadcrumbs $breadcrumbs, Pages $pages): void
    {
        Blade::directive('breadcrumb', function ($expression) {
            return "<?php \\Breadcrumbs::addBreadcrumb(${expression}); ?>";
        });
        Blade::directive('breadcrumb', function ($expression) {
            return "<?php \\Breadcrumbs::addBreadcrumb(${expression}); ?>";
        });
        View::composer('app.banner', function ($view) use ($pages) {
            $view->with('pageSlug', $pages->slug());
        });
        View::composer([
            'app.breadcrumbs',
            'app.index-breadcrumb-title',
        ], function ($view) use ($breadcrumbs) {
            $view->with('breadcrumbs', $breadcrumbs);
        });
        View::composer('app.language', function ($view) use ($pages) {
            $view->with('otherLngData', $pages->otherLngData());
        });
        View::composer('app.nav-menu', function ($view) use ($pages) {
            $view->with('pages', $pages->all());
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
