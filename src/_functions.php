<?php

namespace Abhayagiri;

function getGitVersion()
{
    return trim(exec('git log -n1 --pretty="%h - %ci - %s" HEAD'));
}

function getVersionStamp()
{
    if (\Config::get('abhayagiri.git_versioning')) {
        return trim(exec('git log -n1 --pretty="%h" HEAD'));
    } else {
        return (string) time();
    }
}

function isSSL()
{
    return array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on';
}

function redirect($url, $secure = null)
{
    header("Location: " . redirectUrl($url, $secure));
    exit();
}

function redirectUrl($url, $secure = null)
{
    $parts = parse_url($url);
    if (array_key_exists('host', $parts)) {
        $host = $parts['host'];
    } else {
        $host = parse_url(\Config::get('app.url'))['host'];
        if ($secure === null) {
            $secure = \Config::get('abhayagiri.require_ssl');
        }
    }
    if ($secure === true) {
        $scheme = 'https';
    } elseif ($secure === false) {
        $scheme = 'http';
    } elseif (array_key_exists('scheme', $parts)) {
        $scheme = $parts['scheme'];
    } else {
        if (isSSL()) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }
    }
    $path = $parts['path'];
    if (array_key_exists('query', $parts)) {
        $path .= '?' . $parts['query'];
    }
    if (array_key_exists('fragment', $parts)) {
        $path .= '#' . $parts['fragment'];
    }
    return "$scheme://$host$path";
}

?>
