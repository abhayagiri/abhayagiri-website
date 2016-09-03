<?php

namespace App\Console\Commands;

class ImportDatabase extends ExportBase
{
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
    protected $localdatabaseArchivePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->localdatabaseArchivePath = storage_path('tmp/database.sql.bz2');
    }

    /**
     * Import a database.
     *
     * @return void
     */
    public function handle()
    {
        if (config('app.env') == 'production') {
            throw new \Exception('Cannot run from production environment');
        }

        $this->downloadAndCache(
            config('export.import_database_url'),
            $this->localdatabaseArchivePath,
            'database'
        );

        $host = $this->databaseConfig('host');
        $database = $this->databaseConfig('database');
        $username = $this->databaseConfig('username');
        $password = $this->databaseConfig('password');

        $this->info('Importing database.');
        $this->exec(
            ['mysql', '-u', $username, '-h', $host, '-p', $password],
            "DROP DATABASE $database; CREATE DATABASE $database;"
        );

        $this->exec(
            'bzcat ' . escapeshellarg($this->localdatabaseArchivePath) . ' | ' .
            'mysql ' .
            '-u ' . escapeshellarg($username) . ' ' .
            '-h ' . escapeshellarg($host) . ' ' .
            '-p' . escapeshellarg($password) . ' ' .
            escapeshellarg($database)
        );

        $this->call('migrate');
        $this->call('command:add-admin', [
            'email' => config('abhayagiri.auth.mahapanel_admin')
        ]);
    }
}
