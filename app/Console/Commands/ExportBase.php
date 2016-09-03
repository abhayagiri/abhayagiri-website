<?php

namespace App\Console\Commands;

use Alchemy\Zippy\Adapter\AdapterContainer;
use Alchemy\Zippy\Adapter\GNUTar\TarBz2GNUTarAdapter;
use Alchemy\Zippy\FileStrategy\FileStrategyInterface;
use Alchemy\Zippy\Zippy;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Common functionality for import and export commands.
 */
class ExportBase extends Command
{
    /**
     * Get the base path and prefix of the export file.
     *
     * @param string $type database|media
     * @return string
     */
    public function exportBasePath($type)
    {
        return config('export.path') . '/' . config('export.prefix') .
            '-' . $type;
    }

    /**
     * Create the public export directory.
     *
     * @return void
     */
    public function makeExportDirectory()
    {
        $this->mkdir(config('export.path'));
    }

    /**
     * Remove old files of type.
     *
     * @param string $type database|media
     * @return void
     */
    public function removeOldFiles($type)
    {
        $this->exec(['find', config('export.path'),
            '-name', config('export.prefix') . '-' . $type . '*',
            '-type', 'f', '-ctime', '+' . config('export.keep_days'),
            '-exec', 'rm', '-f', '{}', ';'
        ]);
    }

    /**
     * Get the export configuration.
     *
     * @param string $key
     * @return mixed
     */
    public function exportConfig($key)
    {
        return config('export.' . $key);
    }

    /**
     * Get a datetime stamp suitable for files.
     *
     * @return string
     */
    public function fileDateTime()
    {
        $timezone = new \DateTimeZone(config('app.timezone'));
        $now = new \DateTime('now', $timezone);
        return $now->format('YmdHis');
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
     * @return void
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
        $maxAge = config('export.cache_age');
        if (!file_exists($path) || time() - filemtime($path) > $maxAge) {
            $this->info("Downloading $type.");
            $this->exec(['curl', '-s', '-o', $path, $url]);
        } else {
            $this->info("Using cached $type.");
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

    /**
     * Return a zippy instance.
     *
     * @return \Alchemy\Zippy\Zippy
     */
    protected function zippy()
    {
        $adapters = AdapterContainer::load();
        $zippy = new Zippy($adapters);
        $zippy->addStrategy(new GnuTarBz2FileStrategy($adapters));
        return $zippy;
    }

}

/**
 * Gnu-tar only adapter
 */
class GnuTarBz2Adapter extends TarBz2GNUTarAdapter
{
    /**
     * @inheritdoc
     */
    public static function getDefaultDeflatorBinaryName()
    {
        return array('gtar', 'gnutar', 'tar');
    }

    /**
     * @inheritdoc
     */
    public static function getDefaultInflatorBinaryName()
    {
        return array('gtar', 'gnutar', 'tar');
    }
}

/**
 * Gnu-tar only strategy
 */
class GnuTarBz2FileStrategy implements FileStrategyInterface
{
    protected $container;

    public function __construct(AdapterContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function getAdapters()
    {
        return [GnuTarBz2Adapter::newInstance(
            $this->container['executable-finder'],
            $this->container['resource-manager'],
            $this->container['bsd-tar.inflator'],
            $this->container['bsd-tar.deflator']
        )];
    }

    /**
     * @inheritdoc
     */
    public function getFileExtension()
    {
        return 'tar.bz2';
    }
}
