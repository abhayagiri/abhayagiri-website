<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class Deploy implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    const REPOSITORY_URL = 'https://github.com/abhayagiri/abhayagiri-website';

    /**
     * The deployment stage.
     *
     * @var string
     */
    protected $deployStage;

    /**
     * The deployment URL.
     *
     * @var string
     */
    protected $deployUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deployStage, $deployUrl)
    {
        $this->deployStage = $deployStage;
        $this->deployUrl = $deployUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repositoryRevision = $this->getRepositoryRevision();
        $deployRevision = $this->getDeployRevision();
        Log::debug('repositoryRevision: ' . $repositoryRevision);
        Log::debug('deployRevision: ' . $deployRevision);
        if (!$repositoryRevision) {
            Log::warn('Unable to get repository revision.');
            return;
        }
        if (!$deployRevision) {
            Log::warn('Unable to get deploy revision.');
            return;
        }
        if ($repositoryRevision === $deployRevision) {
            Log::debug('Repository and deploy revisions are the same -- not deploying.');
            return;
        }
        $cmd = 'vendor/bin/dep deploy ' . $this->deployStage;
        $cwd = base_path();
        $timeout = 1800; // 30 minute timeout
        $now = Carbon::now('America/Los_Angeles');
        $logPath = base_path('deployer/builds/' . $now->format('Ymd-his') . '.log');
        $preamble = <<<EOT
Date: {$now->toDateTimeString()}
Stage: {$this->deployStage}
Revision: $repositoryRevision
Command: $cmd
Timeout: $timeout


EOT;
        Log::info('Deploy starting, logging to ' . $logPath);
        $logFile = fopen($logPath, 'w');
        fwrite($logFile, $preamble);
        $process = new Process($cmd, $cwd, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($logFile) {
            fwrite($logFile, $buffer);
            fflush($logFile);
        });
        fclose($logFile);
        Log::info('Deploy complete.');
    }

    /**
     * Return the latest git revision from the main repository.
     *
     * @return string
     */
    protected function getRepositoryRevision()
    {
        $process = new Process('git ls-remote ' . self::REPOSITORY_URL);
        $process->run();
        $firstLine = preg_split('/$\R?^/m', $process->getOutput())[0];
        $revision = preg_split('/\W+/', $firstLine)[0];
        return trim($revision);
    }

    /**
     * Return the latest deployment revision.
     *
     * @return string
     */
    protected function getDeployRevision()
    {
        $raw = \App\Util::downloadToString($deployUrl . '/version');
        $data = json_decode($raw);
        return trim($data->gitRevision);
    }
}
