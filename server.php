<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Redirect /th to trailing slash versions.
if ($uri === '/th') {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $uri . '/');
    return;
}

// Do not match anything under /new/.
if (substr($uri, 0, 5) === '/new/' || $uri === '/new') {
    return false;
}

// Serve React front-end from new/index.html.
if (preg_match('_^/(th/)?(gallery|talks)(/.*)?$_', $uri)) {
    readfile(__DIR__ . '/public/new/index.html');
    return;
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    // Do not match /th/.
    if ($uri !== '/th/') {
        return false;
    }
}

require_once __DIR__.'/public/index.php';
