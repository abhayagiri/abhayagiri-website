<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixLocalDirectories extends Command
{
    protected $fullAccessDirectories = [
        'bootstrap/cache',
        'public/ai-cache',
        'public/media/mahaguild',
        'public/media/audio',
        'public/media/books',
        'public/media/images/books',
        'public/media/images/residents',
        'public/media/images/uploads',
        'storage/app/public',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'storage/tmp',
    ];

    protected $fullAccessFiles = [
        'storage/logs/laravel.log',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-local-dirs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix permissions on for Apache usage';

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
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->fullAccessDirectories as $dir) {
            $cmd = "mkdir -p '$dir' && chmod 0777 '$dir'";
            system($cmd);
        }
        foreach ($this->fullAccessFiles as $file) {
            $cmd = "touch '$file' && chmod 0666 '$file'";
            system($cmd);
        }

    }
}
