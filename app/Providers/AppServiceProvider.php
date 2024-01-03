<?php

namespace App\Providers;

use App\Search\Pages;
use App\Utilities\ImageCache;
use App\Utilities\ImageCacheServer;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();
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

        $this->app->bind('imageCache', ImageCache::class);
        $this->app->singleton(ImageCacheServer::class, function ($app) {
            $type = config('filesystems.disks.spaces.url') ? 'spaces' : 'local';
            return new ImageCacheServer($type);
        });
        $this->app->alias(ImageCacheServer::class, 'imageCacheServer');
    }
}
