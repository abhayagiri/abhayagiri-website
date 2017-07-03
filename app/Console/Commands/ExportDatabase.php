<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Weevers\Path\Path;

use App\Console\Commands\ArchiveBase;
use App\Util;

class ExportDatabase extends ArchiveBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a public database';

    /**
     * The path to the database export.
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
        $basePath = $this->exportsBasePath('database');
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
        $this->info("Exporting database to $relativePath.");
        $this->exportDatabase();
        $this->symlink(basename($this->databaseArchivePath),
            $this->databaseLatestPath);
        $this->removeOldFiles(config('archive.exports_path'), '*-database-*');
    }

    /**
     * Export the database.
     *
     * @return void
     */
    public function exportDatabase()
    {
        if ($this->isWindows()) {
            throw new Exception('Unsupported on Windows');
        }
        $tempPath1 = $this->databaseArchivePath . '.1';
        $tempPath2 = $this->databaseArchivePath . '.2';
        $tempPath3 = $this->databaseArchivePath . '.3';
        try {
            $this->mysqldump($tempPath1, [
                'include-tables' => $this->noDataTables(),
                'add-drop-table' => true,
                'no-data' => true,
            ]);
            $this->mysqldump($tempPath2, [
                'include-tables' => $this->openStatusTables(),
                'add-drop-table' => true,
                'where' => "status = 'Open'",
            ]);
            $this->mysqldump($tempPath3, [
                'include-tables' => $this->remainingTables(),
                'add-drop-table' => true,
            ]);
            $this->exec('cat ' .
                escapeshellcmd($tempPath1) . ' ' .
                escapeshellcmd($tempPath2) . ' ' .
                escapeshellcmd($tempPath3) . ' | bzip2 > ' .
                escapeshellcmd($this->databaseArchivePath)
            );
        } catch (Exception $e) {
            @File::delete($this->databaseArchivePath);
            throw $e;
        } finally {
            @File::delete($tempPath1);
            @File::delete($tempPath2);
            @File::delete($tempPath3);
        }
    }

    /**
     * Get the tables names that are to be exported without data.
     *
     * @return array
     */
    public function noDataTables()
    {
        return config('archive.database.no_data_tables');
    }

    /**
     * Get the tables names that are to be exported with status = 'Open'.
     *
     * @return array
     */
    public function openStatusTables()
    {
        return config('archive.database.open_status_tables');
    }

    /**
     * Get the remaining tables not in noDataTables() and openStatusTables().
     *
     * @return array
     */
    public function remainingTables()
    {
        return array_diff(Util::getTables(),
            $this->noDataTables(),
            $this->openStatusTables()
        );
    }
}
