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
        View::share('breadcrumbs', $this->app->get('breadcrumbs'));
        View::share('pages', $this->app->get('pages'));
        Blade::directive('breadcrumb', function ($expression) {
            return "<?php \\Breadcrumbs::addBreadcrumb(${expression}); ?>";
        });
        Blade::directive('breadcrumb', function ($expression) {
            return "<?php \\Breadcrumbs::addBreadcrumb(${expression}); ?>";
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
