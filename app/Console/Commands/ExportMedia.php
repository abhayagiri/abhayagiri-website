<?php

namespace App\Console\Commands;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ExportMedia extends ExportBase
{
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
     * The path to the media archive.
     *
     * @var string
     */
    public $mediaArchivePath;

    /**
     * The path to the media archive latest symlink.
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
        $basePath = $this->exportBasePath('media');
        $dateTime = $this->fileDateTime();
        $this->mediaArchivePath = "$basePath-$dateTime.tar.bz2";
        $this->mediaLatestPath = "$basePath-latest.tar.bz2";
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
        $this->symlink(basename($this->mediaArchivePath),
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

        $files = [];
        $iterator = new FilterMediaIterator(
            public_path('media'),
            config('export.media.max_size'),
            config('export.media.ignore')
        );
        foreach ($iterator as $subPath => $file) {
            $files[$subPath] = $file->getPathname();
        }

        $this->zippy()->create($this->mediaArchivePath, $files, false);
    }
}

class FilterMediaIterator extends RecursiveIteratorIterator
{
    /**
     * Create a new instance.
     *
     * @param string $mediaPath
     * @param int $maxSize
     * @param array $ignorePatterns
     * @return void
     */
    public function __construct($mediaPath, $maxSize, $ignorePatterns = [])
    {
        $this->maxSize = $maxSize;
        $this->ignorePatterns = $ignorePatterns;
        $this->directoryIterator = new RecursiveDirectoryIterator(
            $mediaPath,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        parent::__construct(
            $this->directoryIterator,
            RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        parent::rewind();
        $this->skip();
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        parent::next();
        $this->skip();
    }

    /**
     * Get the sub-pathname.
     *
     * @see \RecursiveIteratorIterator::getSubPathName()
     */
    public function key()
    {
        return $this->getSubPathName();
    }

    private function skip()
    {
        while ($this->valid()) {
            $file = $this->current();
            $okay = !$file->isDir() && $file->getSize() <= $this->maxSize;
            if ($okay) {
                foreach ($this->ignorePatterns as $pattern) {
                    if (preg_match($pattern, $this->key())) {
                        $okay = false;
                        break;
                    }
                }
            }
            if ($okay) {
                break;
            } else {
                $this->next();
            }
        }
    }
}
