<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Util
{
    /**
     * Return an array of database tables.
     *
     * @return array
     */
    static public function getTables()
    {
        $result = [];
        foreach (DB::select('SHOW TABLES') as $table) {
            foreach ($table as $key => $name) {
                $result[] = $name;
            }
        }
        return $result;
    }

    /**
     * Get the latest git commit short revision.
     *
     * @return string
     */
    static public function gitRevision()
    {
        return trim(exec('git log -n1 --pretty="%h" HEAD'));
    }

    /**
     * Get the latest git commit datetime.
     *
     * @return \DateTime
     */
    static public function gitDateTime()
    {
        $timestamp = trim(exec('git log -n1 --pretty="%ct" HEAD'));
        return new \DateTime("@$timestamp");
    }

    /**
     * Get the latest git commit message.
     *
     * @return string
     */
    static public function gitMessage()
    {
        return trim(exec('git log -n1 --pretty="%s" HEAD'));
    }

    /**
     * Return whether or not the request is being served over SSL.
     *
     * @return bool
     */
    static public function isSSL()
    {
        return array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on';
    }

    /**
     * Redirect to URL.
     *
     * @param string $url URL or path
     * @param bool|null $secure make https if true
     * @return void
     * @see redirectUrl()
     */
    static public function redirect($url, $secure = null)
    {
        header("Location: " . static::redirectUrl($url, $secure));
        exit();
    }

    /**
     * Get a fully-qualified redirect URL.
     *
     * @param string $url URL or path
     * @param bool|null $secure make https if true
     * @return string
     */
    static public function redirectUrl($url, $secure = null)
    {
        $parts = parse_url($url);
        if (array_key_exists('host', $parts)) {
            $host = $parts['host'];
        } else {
            $host = parse_url(config('app.url'))['host'];
            if ($secure === null) {
                $secure = config('abhayagiri.require_ssl');
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

    /**
     * Return a version stamp for use with assets.
     *
     * @return string
     */
    static public function versionStamp()
    {
        if (config('abhayagiri.git_versioning')) {
            return static::gitRevision();
        } else {
            return (string) time();
        }
    }
}
