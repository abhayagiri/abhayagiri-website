<?php

namespace App\Console\Commands;

use Artisan;
use Config;
use Illuminate\Console\Command;

class ImportTestDb extends Command
{

    const MAX_AGE = 86400; // 1 day
    const PUBLIC_DB_URL = 'https://dev.abhayagiri.org/export/db-public-latest.sql';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-test-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import the test database';

    protected $localDbPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->localDbPath = storage_path('tmp/db-public-latest.sql');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Config::get('app.env') == 'production') {
            throw new \Exception('Cannot run from production environment');
        }
        if (!file_exists($this->localDbPath) ||
                time() - filemtime($this->localDbPath) > static::MAX_AGE) {
            $cmd = 'curl -s -o "' . $this->localDbPath . '" "' .
                static::PUBLIC_DB_URL . '"';;
            system($cmd);
        }

        system($cmd);

        Artisan::call('command:add-admin', ['email' => 'root@localhost']);

        return true;
    }
}
