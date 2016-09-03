<?php

namespace App\Console\Commands;

use Alchemy\Zippy\Zippy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ExportMedia extends Command
{
    use ExportTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:export-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a public media archive';

    /**
     * The path to the media export.
     *
     * @var string
     */
    public $mediaPath;

    /**
     * The path to the media export latest symlink.
     *
     * @var string
     */
    public $mediaLatestPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->mediaPath = config('export.path') . '/' .
            config('export.prefix') . '-media-' .
            $this->fileDateTime() . '.tar.bz2';
        $this->mediaLatestPath = config('export.path') . '/' .
            config('export.prefix') . '-media-latest.tar.bz2';
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->makeExportDirectory();
        $this->exportMedia();
        $this->symlink(basename($this->mediaPath),
            $this->mediaLatestPath);
        $this->removeOldFiles('media');
    }

    /**
     * Create a media archive.
     *
     * @return void
     */
    public function exportMedia()
    {
        $this->info('Exporting media.');
        $directoryIterator = new RecursiveDirectoryIterator(
            public_path('media'), RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator,
            RecursiveIteratorIterator::SELF_FIRST);
        $files = [];

        foreach ($iterator as $fileInfo) {
            $destPath = $iterator->getSubPathName();
            $ignore = false;
            foreach (config('export.media.ignore') as $pattern) {
                if (preg_match($pattern, $destPath)) {
                    Log::debug('Ignoring: ' . $destPath);
                    $ignore = true;
                    break;
                }
            }
            if (!$ignore && !$fileInfo->isDir()) {
                $ext = strtolower($fileInfo->getExtension());
                if ($fileInfo->getSize() > config('export.media.max_size')) {
                    Log::debug('Ignoring (large file): ' . $destPath);
                } else {
                    Log::debug('Adding: ' . $destPath);
                    $files[$destPath] = $fileInfo->getPathname();
                }
            }
        }

        $zippy = Zippy::load();
        $archive = $zippy->create($this->mediaPath, $files, false);
    }

}
