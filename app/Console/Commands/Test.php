<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all the application tests';

    /**
     * Run all the tests.
     *
     * @return bool
     */
    public function handle()
    {
        chdir(base_path());
        system(<<<EOF
            (
                set -e
                vendor/bin/phpunit --testdox
                APP_ENV=dusk.local php artisan serve --port=8001 > /dev/null 2>&1 &
                APP_ENV=dusk.local php artisan dusk --testdox
                kill %1 || true
                npm test
            )
EOF
        , $result);
        return $result;
    }
}
