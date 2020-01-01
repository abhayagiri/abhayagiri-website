<?php

namespace App\Providers;

use App\Utilities\DateTimeCarbonTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DateTimeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Carbon::mixin(DateTimeCarbonTrait::class);
        Blade::directive('date', function ($expression) {
            return "<?php echo (new \\Carbon\\Carbon(${expression}))->formatUserDateHtml(); ?>";
        });
        Blade::directive('datetime', function ($expression) {
            return "<?php echo (new \\Carbon\\Carbon(${expression}))->formatUserDateTimeHtml(); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
