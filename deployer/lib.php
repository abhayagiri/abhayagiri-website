<?php

require __DIR__.'/../bootstrap/autoload.php';
require __DIR__.'/TravisWebhookVerifier.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->loadEnvironmentFrom('.env');
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Cocur\BackgroundProcess\BackgroundProcess;

function startDeploy()
{
    $deployerScript = base_path('deployer/deploy-to-staging.sh');
    $lockPath = base_path('deployer/.lock');
    $lockFp = @fopen($lockPath, 'r+');
    if (!$lockFp) {
        $lockFp = fopen($lockPath, 'w+');
    }
    if (!flock($lockFp, LOCK_EX | LOCK_NB)) {
        fclose($lockFp);
        return false;
    }
    fseek($lockFp, 0);
    $pid = trim((string) fread($lockFp, 100));
    if ($pid) {
        $existingProcess = BackgroundProcess::createFromPID($pid);
        if ($existingProcess->isRunning()) {
            fclose($lockFp);
            return false;
        }
    }
    $process = new BackgroundProcess($deployerScript);
    $process->run();
    fseek($lockFp, 0);
    fwrite($lockFp, (string) $process->getPid());
    fclose($lockFp);
    return $process->getPid();
}

function getBuild($build)
{
    if (!preg_match('/^[-_A-Za-z0-9]+$/', $build)) {
        return null;
    }
    $buildPath = base_path('deployer/builds/' . $build . '.txt');
    if (File::exists($buildPath)) {
        return [
            'build' => $build,
            'path' => $buildPath,
            'contents' => File::get($buildPath)
        ];
    } else {
        return null;
    }
}

function getBuilds()
{
    $result = [];
    foreach (File::allFiles(base_path('deployer/builds')) as $buildPath) {
        $parts = pathinfo($buildPath);
        $result[] = [
            'build' => $parts['filename'],
            'path' => $buildPath,
            'contents' => File::get($buildPath),
        ];
    }
    return array_reverse($result);
}

function checkSecret($test)
{
    $secret = env('DEPLOYER_SECRET');
    return ($secret && $secret === $test);
}
