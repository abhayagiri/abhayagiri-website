<?php

if (!defined('LARAVEL_START')) {

    error_reporting(E_ALL | E_STRICT);
    ini_set('error_log', __DIR__.'/../storage/logs/laravel.log');

    require_once __DIR__.'/../bootstrap.php';

    // Setup headers and session

    header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

    session_start();
}

// Setup global variables
foreach (App\Legacy::$GLOBAL_VARIABLES as $key) {
    $$key = '';
}

App\Legacy::setupRequestParams(false);
