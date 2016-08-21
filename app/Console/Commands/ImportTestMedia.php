<?php

namespace App\Console\Commands;

use Config;
use Illuminate\Console\Command;

class ImportTestMedia extends Command
{

    const MAX_AGE = 86400; // 1 day
    const PUBLIC_MEDIA_URL = 'https://dev.abhayagiri.org/export/media-latest.zip';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-test-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and import the test media files';

    protected $localMediaPath;
    protected $mediaPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->localMediaPath = storage_path('tmp/media-latest.zip');
        $this->mediaPath = public_path('media');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (Config::get('app.env') == 'production') {
            throw new \Exception('Cannot run from production environment');
        }
        if (!file_exists($this->localMediaPath) ||
                time() - filemtime($this->localMediaPath) > static::MAX_AGE) {
            $cmd = 'curl -s -o "' . $this->localMediaPath . '" "' .
                static::PUBLIC_MEDIA_URL . '"';
            system($cmd);
        }

        $cmd = 'mkdir -p "' . $this->mediaPath . '" && ' .
            'cd "' . $this->mediaPath . '" && ' .
            'unzip -q -o "' . $this->localMediaPath . '"';
        system($cmd);

        return true;
    }
}
