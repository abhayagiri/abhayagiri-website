<?php

require_once __DIR__.'/../lib.php';

try {
    // Courtesy of https://gist.github.com/joemaller/e5e0b737a321d69ae2fc
    $input = file_get_contents('php://input');
    $secret = env('DEPLOYER_SECRET');
    $signature = 'sha1=' . hash_hmac('sha1', $input, $secret);
    $result = hash_equals($signature, array_get($_SERVER, 'HTTP_X_HUB_SIGNATURE', ''));
    if ($result) {
        $job = new App\Jobs\Deploy('staging', 'https://staging.abhayagiri.org');
        $result = dispatch($job);
        if ($result) {
            print 'Started deploy.';
        } else {
            print 'Failed to start deploy.';
        }
    } else {
        print 'Invalid webhook.';
    }
} catch (Exception $e) {
    die($e);
}
