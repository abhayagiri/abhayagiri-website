<?php

namespace App\Console\Commands;

use App\Models\Talk;
use Illuminate\Console\Command;

class SyncMp3Tags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-mp3-tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update mp3 tag data for all Talks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->syncMp3Tags();
    }

    protected function syncMp3Tags()
    {
        Talk::all()->each(function (Talk $talk) {
            $talk->updateId3Tags();
        });
    }
}
