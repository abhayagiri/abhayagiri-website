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
        system('kill $(lsof -t -i:8001) > /dev/null 2>&1');
        system('nohup php artisan serve --port=8001 --env=dusk.local > /dev/null 2>&1 &');
        system('vendor/bin/phpunit --testdox', $result);
        if ($result) {
            return $result;
        }
        system('php artisan dusk --env=dusk.local --testdox', $result);
        system('kill $(lsof -t -i:8001) > /dev/null 2>&1');
        if ($result) {
            return $result;
        }
        system('npm test', $result);
        return $result;
    }
}
