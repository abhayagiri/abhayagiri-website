<?php

namespace App\Console\Commands;

use Config;
use Illuminate\Console\Command;

class ImportDatabase extends Command
{
    use ExportTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import the public database';

    /**
     * The local database path.
     *
     * @var string
     */
    protected $localDatabasePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->localDatabasePath = storage_path('tmp/database.sql.bz2');
    }

    /**
     * Import a database.
     *
     * @return void
     */
    public function handle()
    {
        if (Config::get('app.env') == 'production') {
            throw new \Exception('Cannot run from production environment');
        }

        $this->downloadAndCache(config('export.import_database_url'),
            $this->localDatabasePath, 'database');

        $host = $this->databaseConfig('host');
        $database = $this->databaseConfig('database');
        $username = $this->databaseConfig('username');
        $password = $this->databaseConfig('password');

        $databaseSql = "DROP DATABASE $database; CREATE DATABASE $database;";

        $this->info('Importing database.');
        $this->exec(
            'echo ' . escapeshellarg($databaseSql) . ' | ' .
            'mysql ' .
            '-u ' . escapeshellarg($username) . ' ' .
            '-h ' . escapeshellarg($host) . ' ' .
            '-p' . escapeshellarg($password)
        );

        $this->exec(
            'bzcat ' . escapeshellarg($this->localDatabasePath) . ' | ' .
            'mysql ' .
            '-u ' . escapeshellarg($username) . ' ' .
            '-h ' . escapeshellarg($host) . ' ' .
            '-p' . escapeshellarg($password) . ' ' .
            escapeshellarg($database)
        );

        $this->call('migrate');
        $this->call('command:add-admin', [
            'email' => Config::get('abhayagiri.auth.mahapanel_admin')
        ]);
    }
}
