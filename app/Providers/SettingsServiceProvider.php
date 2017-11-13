<?php

// This file is based on:
// https://github.com/Laravel-Backpack/Settings/blob/master/src/app/Http/Controllers/SettingCrudController.php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (!\App::runningInConsole() && count(Schema::getColumnListing('settings'))) {
            self::setupSettings();
        }
    }

    /**
     * Apply settings to config.
     *
     * @return void
     */
    public static function setupSettings()
    {
        $settings = Setting::all();
        foreach ($settings as $key => $setting) {
            Config::set('settings.'.$setting->key, $setting->value);
        }
    }
}
