<?php

namespace App\Providers;

use App\Utilities\Id3WriterHelper;
use Illuminate\Support\Facades\App;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('getID3Writer', function () {
            return new Id3WriterHelper;
        });
    }
}
