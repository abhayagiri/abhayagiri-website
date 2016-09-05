<?php

namespace App\Console\Commands;

use App\Console\Commands\ArchiveBase;

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

        $this->info('Importing media.');
        $this->mkdir($this->mediaPath);
        $this->exec(['tar',
            '-xjf', $this->localMediaArchivePath,
            '--strip-components=1',
            '-C', $this->mediaPath
        ]);
    }
}
