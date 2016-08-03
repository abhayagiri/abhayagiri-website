<?php

namespace Abhayagiri;

function getMahapanelRoot()
{
    return getWebRoot(Config::get('requireMahapanelSSL')) . '/mahapanel';
}

function getGitVersion()
{
    return trim(exec('git log -n1 --pretty="%h - %ci - %s" HEAD'));
}

function getVersionStamp()
{
    if (Config::get('development')) {
        return (string) time();
    } else {
        return trim(exec('git log -n1 --pretty="%h" HEAD'));
    }
}

function getRootDir()
{
    return __DIR__ . '/..';
}

function getMediaDir()
{
    return getRootDir() . '/public/media';
}

function getWebRoot($ssl = null)
{
    if ($ssl === null) {
        $ssl = Config::get('requireSSL');
    }
    return ($ssl ? 'https' : 'http') . '://' . Config::get('host');
}

function isSSL()
{
    return array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on';
}

function redirect($url, $ssl = null)
{
    header("Location: " . redirectUrl($url, $ssl));
    exit();
}

function redirectUrl($url, $ssl = null)
{
    $parts = parse_url($url);
    if (array_key_exists('host', $parts)) {
        $host = $parts['host'];
    } else {
        $host = Config::get('host');
        if ($ssl === null) {
            $ssl = Config::get('requireSSL');
        }
    }
    if ($ssl === true) {
        $scheme = 'https';
    } elseif ($ssl === false) {
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
