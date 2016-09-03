<?php

namespace App\Console\Commands;

use Alchemy\Zippy\Zippy;
use Config;
use Illuminate\Console\Command;

class ImportMedia extends Command
{
    use ExportTrait;

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
     * The local media path.
     *
     * @var string
     */
    protected $localMediaPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->localMediaPath = storage_path('tmp/media.tar.bz2');
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
        $this->downloadAndCache(config('export.import_media_url'),
            $this->localMediaPath, 'media');

        $this->mkdir(public_path('media'));

        $this->info('Importing media.');
        $zippy = Zippy::load();
        $archive = $zippy->open($this->localMediaPath);
        $archive->extract(public_path('media'));
    }
}
