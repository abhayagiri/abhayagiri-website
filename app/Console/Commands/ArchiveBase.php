<?php

namespace App\Console\Commands;

use DateTime;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
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
        @File::makeDirectory(config('archive.exports_path'), 0777, true);
    }

    /**
     * Remove old files of type.
     *
     * @param string $type database|media
     * @return void
     */
    public function removeOldFiles($path, $match = '*')
    {
        if ($this->isWindows()) {
            throw new Exception('Unsupported on Windows');
        }
        // $path should have '/' appended in order for a symlink to
        // to the directory be followed.
        $this->exec(['find', $path . '/', '-name', $match,
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
        Log::debug("Symlinking: $srcPath -> $targetPath.");
        @File::delete($targetPath);
        @symlink($srcPath, $targetPath);
    }

    /**
     * Download and cache $url to $path.
     *
     * @param string $url
     * @param string $path
     * @param string $type
     * @param int $minsize
     * @return void
     */
    public function downloadAndCache($url, $path, $type = 'file', $minsize = 100000)
    {
        $maxAge = config('archive.cache_age');
        if (!File::exists($path) ||
                File::size($path) < $minsize ||
                time() - File::lastmodified($path) > $maxAge) {
            $this->download($url, $path, $type);
            if (File::size($path) < $minsize) {
                File::delete($path);
                throw new \Exception("Downloaded file from $url less than minimum size $minsize.");
            }
        }
    }

    /**
     * Download $url to $path.
     *
     * @param string $url
     * @param string $path
     * @param string $type
     * @return void
     */
    public function download($url, $path, $type = 'file')
    {
        $this->info("Downloading $type: $url");
        $fp = fopen($path, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CAINFO, config_path("cacert.pem"));
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $result = curl_exec($ch);
        if (curl_exec($ch) === false) {
            $error = null;
        } else {
            $error = curl_error($ch);
        }
        curl_close($ch);
        if ($error) {
            throw new \Exception($error);
        }
        if (!File::exists($path)) {
            throw new \Exception("Downloaded file from $url did not create $path.");
        }
    }

    /**
     * Return the decompressed contents of $path.
     *
     * @param string $path
     */
    public function bzread($path)
    {
        $fp = bzopen($path, 'r');
        $result = "";
        while ($buffer = bzread($fp, 40960)) {
            $result .= $buffer;
        }
        bzclose($fp);
        return $result;
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

    /**
     * Return $path relative to base_path().
     *
     * @return string
     */
    protected function rel($path)
    {
        return Path::relative(base_path(), $path);
    }

    /**
     * Returns whether or not this is Windows.
     *
     * @return bool
     */
    protected function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
