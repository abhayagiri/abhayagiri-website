<?php

namespace App\Console\Commands;

use \PharData;
use App\Console\Commands\ArchiveBase;
use Illuminate\Support\Facades\File;
use Weevers\Path\Path;

class ImportMedia extends ArchiveBase
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import the public media archive';

    /**
     * The path to the media directory.
     *
     * @var string
     */
    public $mediaPath;

    /**
     * The local media archive path.
     *
     * @var string
     */
    protected $localMediaArchivePath;

    /**
     * The temporary directory containing the extracted media files.
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
        $this->mediaPath = public_path('media');
        $this->localMediaArchivePath = config('archive.import_media_path');
    }

    /**
     * Import media.
     *
     * @return void
     */
    public function handle()
    {
        if (config('app.env') == 'production') {
            throw new \Exception('Cannot run from production environment');
        }

        $this->downloadAndCache(
            config('archive.import_media_url'),
            $this->localMediaArchivePath,
            'media'
        );

        if ($this->isWindows()) {
            $tmpStore = config('archive.media.temp_base_path') . DIRECTORY_SEPARATOR . 'media';
            $tarPath = $tmpStore . DIRECTORY_SEPARATOR . 'media.tar';
            @File::makeDirectory($tmpStore, 0777, true);
            File::cleanDirectory($tmpStore);
            $this->info('Decompressing ' . $this->rel($this->localMediaArchivePath) .
                        ' to ' . $this->rel($tarPath) . '.');
            $this->exec(['7z', 'x', $this->localMediaArchivePath, '-o' . $tmpStore]);
            $this->info('Extracting ' . $this->rel($tarPath) .
                        ' to ' .  $this->rel($tmpStore) . '.');
            $this->exec(['7z', 'x', $tarPath, '-o' . $tmpStore]);
            $archiveDir = File::directories($tmpStore)[0];
            $this->info('Copying media from ' . $this->rel($tmpStore) .
                        ' to ' . $this->rel($this->mediaPath) . '.');
            File::copyDirectory($archiveDir, $this->mediaPath);
            $this->info('Cleaning temporary directory ' . $this->rel($tmpStore) . '.');
            File::deleteDirectory($tmpStore);
        } else {
            $this->info('Extracting media from ' . $this->rel($this->localMediaArchivePath) .
                        ' to ' . $this->rel($this->mediaPath) . '.');
            $this->exec(['tar',
                '-xjf', $this->localMediaArchivePath,
                '--strip-components=1',
                '-C', $this->mediaPath
            ]);
        }
    }
}
