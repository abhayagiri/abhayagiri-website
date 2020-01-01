<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Weevers\Path\Path;

class ExportMedia extends Command
{
    use ArchiveTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-media';

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
     * The temporary directory containing hard links of the media files.
     *
     * @var string
     */
    public $tempPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $basePath = $this->exportsBasePath('media');
        $dateTime = $this->fileDateTime();
        $this->tempPath = config('archive.media.temp_base_path') . '/' .
            config('archive.prefix') . '-media-' . $dateTime;
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
        $relativePath = Path::relative(base_path(), $this->mediaArchivePath);
        $this->info("Exporting media to $relativePath.");
        $this->exportMedia();
        $this->symlink(
            basename($this->mediaArchivePath),
            $this->mediaLatestPath
        );
        $this->removeOldFiles(config('archive.exports_path'), '*-media-*');
    }

    /**
     * Create a media archive.
     *
     * @return void
     */
    public function exportMedia()
    {
        if ($this->isWindows()) {
            throw new Exception('Unsupported on Windows');
        }
        $files = [];
        $iterator = new FilterMediaIterator(
            public_path('media'),
            config('archive.media.max_size'),
            config('archive.media.ignore')
        );
        try {
            foreach ($iterator as $subPath => $file) {
                $destPath = $this->tempPath . '/' . $subPath;
                $destDir = dirname($destPath);
                if (!is_dir($destDir)) {
                    File::makeDirectory($destDir, 0777, true);
                }
                link($file->getPathname(), $destPath);
            }
            $this->exec(['tar',
                '-C', dirname($this->tempPath),
                '-cjf', $this->mediaArchivePath,
                basename($this->tempPath)
            ]);
        } finally {
            $this->exec(['rm', '-rf', $this->tempPath]);
        }
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
     *
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
