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
        system('vendor/bin/phpunit --testdox', $result);
        if ($result) return $result;
        system('vendor/bin/codecept run', $result);
        if ($result) return $result;
        system('npm test');
        if ($result) return $result;
        system('php artisan dusk --testdox', $result);
        return $result;
    }
}
