<?php

namespace App\Console\Commands;

class ImportMedia extends ExportBase
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
    protected $description = 'Download and import the test media files';

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
        $this->localMediaArchivePath = storage_path('tmp/media.tar.bz2');
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
            config('export.import_media_url'),
            $this->localMediaArchivePath,
            'media'
        );

        $this->info('Importing media.');
        $this->mkdir($this->mediaPath);
        $archive = $this->zippy->open($this->localMediaArchivePath);
        $archive->extract($this->mediaPath);
    }
}
