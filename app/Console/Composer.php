<?php

namespace App\Console;

use Composer\Script\Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

class Composer
{
    /**
     * Equivalent of `php artisan key:generate`.
     *
     * @param  \Composer\Script\Event $event
     * @return void
     */
    static public function artisanKeyGenerate(Event $event)
    {
        static::artisanRun($event, 'key:generate');
    }

    /**
     * Equivalent of `php artisan optimize`.
     *
     * @param  \Composer\Script\Event $event
     * @return void
     */
    static public function artisanOptimize(Event $event)
    {
        static::artisanRun($event, 'optimize');
    }

    /**
     * A static function helper for composer scripts so that we can use the
     * php version running deployer/composer instead of the default system
     * version.
     *
     * @param  \Composer\Script\Event $event
     * @param  string $command
     * @return void
     */
    static protected function artisanRun(Event $event, $command)
    {
        require_once __DIR__.'/../../bootstrap/autoload.php';
        $app = require __DIR__.'/../../bootstrap/app.php';
        $kernel = $app->make(Kernel::class);
        $kernel->call($command);
    }
}
