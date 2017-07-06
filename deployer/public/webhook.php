<?php

require_once __DIR__.'/../lib.php';

$payload = array_get($_POST, 'payload');
$signature = array_get($_SERVER, 'HTTP_SIGNATURE');

if (TravisWebhookVerifier::verify($payload, $signature)) {
    $data = json_decode($payload);
    if ($data->status === 0) {
        if ($pid = startDeploy()) {
            print "Started deploy ($pid).";
        } else {
            print 'Failed to start deploy.';
        }
    } else {
        print 'Ignoring non-successful build.';
    }
} else {
    echo 'Invalid webhook.';
}
