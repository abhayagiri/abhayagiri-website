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
        $this->updateRepository();
        $revision = \App\Util::gitRevision();
        $message = \App\Util::gitMessage();
        $now = Carbon::now('America/Los_Angeles');
        $deployRevision = $this->getDeployRevision();
        if (!$deployRevision) {
            Log::warn('Unable to get deploy revision.');
            return;
        }
        if ($revision === $deployRevision) {
            Log::debug('Repository and deploy revisions are the same -- not deploying.');
            return;
        }
        $cmd = 'vendor/bin/dep deploy ' . $this->deployStage;
        $timeout = 1800; // 30 minute timeout
        $logPath = base_path('deployer/builds/' . $now->format('Ymd-his') . '.log');
        $preamble = <<<EOT
Date: {$now->toDateTimeString()}
Stage: {$this->deployStage}
Revision: $revision
Message: $message


EOT;
        Log::info('Deploy starting, logging to ' . $logPath);
        $logFile = fopen($logPath, 'w');
        fwrite($logFile, $preamble);
        $cmd = $this->wrapTtyCommand($cmd);
        $process = new Process($cmd, base_path(), null, null, $timeout);
        $process->run(function ($type, $buffer) use ($logFile) {
            fwrite($logFile, $buffer);
            fflush($logFile);
        });
        fclose($logFile);
        Log::info('Deploy complete.');
    }

    /**
     * Returns whether or not this system has Linux script.
     *
     * See https://stackoverflow.com/questions/1401002/trick-an-application-into-thinking-its-stdin-is-interactive-not-a-pipe
     *
     * @return bool
     */
    protected function wrapTtyCommand($cmd)
    {
        $process = new Process('script -V');
        try {
            $process->mustRun();
            $cmd = 'script --return -c "' . $cmd . '" /dev/null';
        } catch (\Exception $e) {
        }
        return $cmd;
    }

    /**
     * Update local git repository.
     *
     * @return void
     */
    protected function updateRepository()
    {
        $process = new Process('git fetch --all', base_path());
        $process->mustRun();
        $process = new Process('git reset --hard origin/master', base_path());
        $process->mustRun();
    }

    /**
     * Return the latest deployment revision.
     *
     * @return string
     */
    protected function getDeployRevision()
    {
        $raw = \App\Util::downloadToString($this->deployUrl . '/version');
        $data = json_decode($raw);
        return trim($data->gitRevision);
    }
}
