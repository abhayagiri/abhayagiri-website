<?php

namespace App\Providers;

use App\YouTubeSync;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class YouTubeSyncServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('youtube.sync', function ($app) {
            $config = $app->get('config');
            return YouTubeSync::create(
                $config->get('abhayagiri.youtube_channel_id'),
                $config->get('abhayagiri.youtube_oauth_client_id'),
                $config->get('abhayagiri.youtube_oauth_client_secret'),
                $app->get('url')->route('youtube.sync.callback')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [YouTubeSync::class];
    }
}
