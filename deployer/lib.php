<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->loadEnvironmentFrom('.env');
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function getBuild($build)
{
    if (!preg_match('/^[-_A-Za-z0-9]+$/', $build)) {
        return null;
    }
    $buildPath = base_path('deployer/builds/' . $build . '.log');
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
    usort($result, function($a, $b) {
        return $b['build'] <=> $a['build'];
    });
    return $result;
}

function clearOldBuilds()
{
    $keepLife = 24 * 60 * 60; // 24 hours
    foreach (File::allFiles(base_path('deployer/builds')) as $buildPath) {
        $mtime = File::lastModified($buildPath);
        if ($mtime && (time() - $mtime > $keepLife)) {
            File::delete($buildPath);
        }
    }
}

function deployStaging()
{
    $job = new App\Jobs\Deploy('staging', 'https://staging.abhayagiri.org');
    return dispatch($job);
}

function deployProduction()
{
    $job = new App\Jobs\Deploy('production', 'https://www.abhayagiri.org');
    return dispatch($job);
}
