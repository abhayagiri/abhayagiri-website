<?php

namespace App\Console\Commands;

use App\Util;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use Weevers\Path\Path;

class ExportDatabase extends Command
{
    use ArchiveTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-database';

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
        $this->symlink(
            basename($this->databaseArchivePath),
            $this->databaseLatestPath
        );
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
        $database = config('archive.database');
        $database['1 = 1'] = $this->remainingTables();
        $tempPaths = [];
        $i = 0;
        try {
            foreach ($database as $whereClause => $tables) {
                $tempPaths[$i] = $tempPath =
                    $this->databaseArchivePath . '.' . $i;
                $this->mysqldump($tempPath, [
                    'include-tables' => $tables,
                    'add-drop-table' => true,
                    'where' => $whereClause,
                ]);
                $i++;
            }

            $this->exec(
                'cat ' .
                escapeshellcmd(implode($tempPaths, ' ')) .
                ' | bzip2 > ' .
                escapeshellcmd($this->databaseArchivePath)
            );
        } catch (Exception $e) {
            @File::delete($this->databaseArchivePath);
            throw $e;
        } finally {
            foreach ($tempPaths as $tempPath) {
                @File::delete($tempPath);
            }
        }
    }

    /**
     * Get the remaining tables not in listed in archive.database.
     *
     * @return array
     */
    public function remainingTables()
    {
        $args = array_merge(
            [Util::getTables()],
            array_values(config('archive.database'))
        );
        return call_user_func_array('array_diff', $args);
    }
}
