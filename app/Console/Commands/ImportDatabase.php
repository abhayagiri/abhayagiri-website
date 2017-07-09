<?php

namespace App\Console\Commands;

use App\Console\Commands\ArchiveBase;
use Weevers\Path\Path;

class ImportDatabase extends ArchiveBase
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
            config('archive.import_database_url'),
            $this->localdatabaseArchivePath,
            'database'
        );

        $host = $this->databaseConfig('host');
        $database = $this->databaseConfig('database');
        $username = $this->databaseConfig('username');
        $password = $this->databaseConfig('password');

        if (config('app.env') == 'local') {
            // try to grant privileges
            try {
                $this->exec(
                    ['mysql', '-u', 'root'],
                    "GRANT ALL on $database.* TO '$username'@'$host' IDENTIFIED BY '$password';FLUSH PRIVILEGES;"
                );
            } catch (\Exception $e) {
                $this->warn('Could not grant MySQL access to abhayagiri via root.');
            }
        }

        $relativePath = Path::relative(base_path(), $this->localdatabaseArchivePath);
        $this->info("Importing database from $relativePath.");
        $this->exec(
            ['mysql', '-u', $username, '-h', $host, '-p' . $password],
            "DROP DATABASE IF EXISTS $database; CREATE DATABASE $database;"
        );

        $this->exec(
            ['mysql', '-u', $username, '-h', $host, '-p' . $password, $database],
            $this->bzread($this->localdatabaseArchivePath)
        );

        $this->call('migrate');
        $this->call('command:add-admin', [
            'email' => config('abhayagiri.auth.mahapanel_admin')
        ]);
    }
}
