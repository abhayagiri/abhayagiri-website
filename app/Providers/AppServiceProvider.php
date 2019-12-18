<?php

namespace App\Providers;

use App\Search\Pages;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Pages::bootSearchable();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('getID3Writer', function () {
            return new Id3WriterHelper;
        });
    }
}
