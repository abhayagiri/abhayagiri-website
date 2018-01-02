<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ifsnop\Mysqldump\Mysqldump;
use Weevers\Path\Path;

class BackupDatabase extends Command
{
    use ArchiveTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    /**
     * The path to the database archive.
     *
     * @var string
     */
    public $databaseArchivePath;

    /**
     * The path to the database export latest symlink.
     *
     * @var string
     */
    public $databaseLatestPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $basePath = config('archive.backup_path') . '/' .
            config('archive.prefix') . '-private-database';
        $dateTime = $this->fileDateTime();
        $this->databaseArchivePath = "$basePath-$dateTime.sql.bz2";
        $this->databaseLatestPath = "$basePath-latest.sql.bz2";
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $relativePath = Path::relative(base_path(), $this->databaseArchivePath);
        $this->info("Backing up database to $relativePath.");
        $this->backupDatabase();
        $this->symlink(basename($this->databaseArchivePath),
            $this->databaseLatestPath);
        $this->removeOldFiles(config('archive.backup_path'), '*-database-*');
    }

    /**
     * Backup the database.
     *
     * @return void
     */
    public function backupDatabase()
    {
        $this->mysqldump($this->databaseArchivePath, [
            'add-drop-table' => true,
            'compress' => Mysqldump::BZIP2,
        ]);
    }
}
