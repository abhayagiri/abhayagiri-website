<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class CreateStamp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stamp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make the revision stamp file';

    /**
     * The output stamp path (relative to base).
     *
     * @var string
     */
    protected $stampFile = '.stamp.json';

    /**
     * The manifest JSON path (relative to base).
     *
     * @var string
     */
    protected $manifestFile = 'public/new/manifest.json';

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
        $stampPath = base_path($this->stampFile);
        $this->info('Creating ' . $this->stampFile);
        File::put($stampPath, json_encode($this->getStamp()));
    }

    protected function getGitInfo()
    {
        $cmd = 'git log -n1 --pretty="%H:%ct:%s" HEAD';
        $process = new Process($cmd, base_path());
        $output = trim($process->mustRun()->getOutput());
        $parts = explode(':', $output, 3);
        return [
            'revision' => $parts[0],
            'timestamp' => $parts[1],
            'message' => $parts[2],
        ];
    }

    protected function getManifest()
    {
        $manifestPath = base_path($this->manifestFile);
        $manifestJson = File::get($manifestPath);
        return json_decode($manifestJson, true);
    }

    protected function getStamp()
    {
        $gitInfo = $this->getGitInfo();
        $manifest = $this->getManifest();
        $chunkHash = preg_replace('_^new/bundle-(.+)\.js$_', '$1', $manifest['app.js']);
        return array_merge($gitInfo, [
            'manifest' => $manifest,
            'chunkHash' => $chunkHash,
        ]);
    }
}
