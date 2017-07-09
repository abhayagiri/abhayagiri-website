<?php

require_once __DIR__.'/../lib.php';

use GitHubWebhook\Handler;

$handler = new Handler(env('DEPLOYER_SECRET'), base_path());

if ($handler->handle()) {
    $job = new App\Jobs\Deploy('staging', 'https://staging.abhayagiri.org');
    $result = dispatch($job);
    if ($result) {
        print 'Started deploy.';
    } else {
        print 'Failed to start deploy.';
    }
} else {
    echo 'Invalid webhook.';
}
