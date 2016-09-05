<?php

namespace App\Console\Commands;

use DateTime;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Weevers\Path\Path;

/**
 * Common functionality for archive commands.
 */
class ArchiveBase extends Command
{
    /**
     * Get the base path and prefix of the export file.
     *
     * @param string $type database|media
     * @return string
     */
    public function exportsBasePath($type)
    {
        return config('archive.exports_path') . '/' . config('archive.prefix') .
            '-public-' . $type;
    }

    /**
     * Create the public export directory.
     *
     * @return void
     */
    public function makeExportDirectory()
    {
        $this->mkdir(config('archive.exports_path'));
    }

    /**
     * Remove old files of type.
     *
     * @param string $type database|media
     * @return void
     */
    public function removeOldFiles($path, $match = '*')
    {
        $this->exec(['find', $path, '-name', $match,
            '-type', 'f', '-ctime', '+' . config('archive.keep_days'),
            '-exec', 'rm', '-f', '{}', ';'
        ]);
    }

    /**
     * Get a datetime stamp suitable for files.
     *
     * @return string
     */
    public function fileDateTime()
    {
        return (new DateTime('now'))->format('YmdHis');
    }

    /**
     * Get the MySQL database configuration.
     *
     * @param string $key
     * @return mixed
     */
    public function databaseConfig($key)
    {
        return config('database.connections.mysql.' . $key);
    }

    /**
     * Get the MySQL database DSN.
     *
     * @return string
     */
    public function databaseDsn()
    {
        $dsn = 'mysql:host=' . $this->databaseConfig('host');
        if ($port = $this->databaseConfig('port')) {
            $dsn .= ';port=' . $port;
        }
        $dsn .= ';dbname=' . $this->databaseConfig('database');
        return $dsn;
    }

    /**
     * Execute command.
     *
     * @param string|array $command
     * @param string $input
     * @return string output
     * @see https://github.com/pastuhov/php-exec-command
     */
    public function exec($command, $input = null)
    {
        if (is_array($command)) {
            $builder = new ProcessBuilder($command);
            $process = $builder->getProcess();
            $command = $process->getCommandLine();
        } else {
            $process = new Process($command);
        }
        $process->setInput($input);
        Log::debug('Executing: ' . $command);
        $process->mustRun();
        return $process->getOutput();
    }

    /**
     * Symlink.
     *
     * @param string $path
     * @return void
     */
    public function symlink($srcPath, $targetPath)
    {
        $this->exec(['ln', '-sf', $srcPath, $targetPath]);
    }

    /**
     * Make directory.
     *
     * @param string $path
     * @return void
     */
    public function mkdir($path)
    {
        $this->exec(['mkdir', '-p', $path]);
    }

    /**
     * Unlink a file.
     *
     * @param string $path
     * @return void
     */
    public function unlink($path)
    {
        $this->exec(['rm', '-f', $path]);
    }

    /**
     * Download and cache $url to $path.
     *
     * @param string $url
     * @param string $path
     * @param string $type
     * @return void
     */
    public function downloadAndCache($url, $path, $type = 'file')
    {
        $maxAge = config('archive.cache_age');
        if (!file_exists($path) || time() - filemtime($path) > $maxAge) {
            $this->info("Downloading $type from $url.");
            $this->exec(['curl', '-s', '-o', $path, $url]);
        }
    }

    /**
     * Run a MySQL dump.
     *
     * @param string $path the output path
     * @param array $options
     * @return void
     * @see \Ifsnop\Mysqldump\Mysqldump
     */
    protected function mysqldump($path, array $options)
    {
        $mysqldump = new Mysqldump($this->databaseDsn(),
            $this->databaseConfig('username'),
            $this->databaseConfig('password'),
            $options);
        $mysqldump->start($path);
    }
}
