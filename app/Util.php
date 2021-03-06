<?php

namespace App;

use App\Utilities\AbridgeTrait;
use App\Utilities\AssociatedModels;
use App\Utilities\DatabaseTrait;
use App\Utilities\DateTimeTrait;
use App\Utilities\EncodingTrait;
use App\Utilities\MonkNameTrait;
use App\Utilities\RoutingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Util
{
    use AssociatedModels;
    use AbridgeTrait;
    use DatabaseTrait;
    use DateTimeTrait;
    use EncodingTrait;
    use MonkNameTrait;
    use RoutingTrait;

    /**
     * Convert a date assumed to be in pacific time zone to UTC.
     *
     * @param string $date
     * @param string $time
     *
     * @return Carbon\Carbon
     */
    public static function createDateFromPacificDb($date, $time = '12:00:00')
    {
        return static::createDateTimeFromPacificDb($date . ' ' . $time);
    }

    /**
     * Convert a date/time assumed to be in pacific time zone to UTC.
     *
     * @param string $datetime
     *
     * @return Carbon\Carbon
     */
    public static function createDateTimeFromPacificDb($datetime)
    {
        return (new Carbon($datetime, 'America/Los_Angeles'))
            ->tz('UTC');
    }

    /**
     * Return whether development/test bypass is available.
     *
     * @return bool
     */
    public static function devBypassAvailable()
    {
        return in_array(Config::get('app.env'), ['local', 'test']) &&
            !!Config::get('abhayagiri.auth.mahapanel_bypass');
    }

    /**
     * Download and return the content of $url.
     *
     * @param string $url
     *
     * @return string
     */
    public static function downloadToString($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return static::downloadCommon($ch);
    }

    /**
     * Download the content of $url to $path.
     *
     * @param string $url
     * @param string $path
     *
     * @return bool
     */
    public static function downloadToFile($url, $path)
    {
        $fp = fopen($path, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        return static::downloadCommon($ch);
    }

    private static function downloadCommon($ch)
    {
        try {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_CAINFO, config_path("cacert.pem"));
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            $result = curl_exec($ch);
            if ($result === false) {
                $error = curl_error($ch);
            } else {
                $error = null;
            }
        } finally {
            curl_close($ch);
        }
        if ($error) {
            throw new \Exception($error);
        }
        return $result;
    }

    /**
     * Get information about the latest commit from .stamp.php.
     *
     * @return array
     */
    public static function getStamp()
    {
        $stampPath = base_path('.stamp.json');
        if (File::exists($stampPath)) {
            return json_decode(File::get($stampPath), true);
        } else {
            return [
                'revision' => '1234567890123456789012345678901234567890',
                'timestamp' => time(),
                'message' => 'N/A',
            ];
        }
    }

    /**
     * Return an array of database tables.
     *
     * @return array
     */
    public static function getTables()
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
     * Get the latest git commit revision.
     *
     * @return string
     */
    public static function gitRevision()
    {
        return static::getStamp()['revision'];
    }

    /**
     * Get the latest git commit datetime.
     *
     * @return \DateTime
     */
    public static function gitDateTime()
    {
        return new \DateTime('@' . static::getStamp()['timestamp']);
    }

    /**
     * Get the latest git commit message.
     *
     * @return string
     */
    public static function gitMessage()
    {
        return static::getStamp()['message'];
    }

    /**
     * Return whether or not the request is being served over SSL.
     *
     * @return bool
     */
    public static function isSSL()
    {
        return array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on';
    }

    /**
     * Redirect to URL.
     *
     * @param string $url URL or path
     * @param bool|null $secure make https if true
     *
     * @return void
     *
     * @see redirectUrl()
     */
    public static function redirect($url, $secure = null)
    {
        header("Location: " . static::redirectUrl($url, $secure));
        exit();
    }

    /**
     * Get a fully-qualified redirect URL.
     *
     * @param string $url URL or path
     * @param bool|null $secure make https if true
     *
     * @return string
     */
    public static function redirectUrl($url, $secure = null)
    {
        $parts = parse_url($url);
        if (array_key_exists('host', $parts)) {
            $host = $parts['host'];
            $port = '';
        } else {
            $appParts = parse_url(Config::get('app.url'));
            $host = $appParts['host'];
            if ($port = array_get($appParts, 'port')) {
                $port = ":$port";
            }
        }
        if ($secure === true) {
            $scheme = 'https';
        } elseif ($secure === false) {
            $scheme = 'http';
        } elseif (array_key_exists('scheme', $parts)) {
            $scheme = $parts['scheme'];
        } else {
            if (static::isSSL()) {
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
        return "$scheme://$host$port$path";
    }

    /**
     * Return a version stamp for use with assets.
     *
     * @return string
     */
    public static function versionStamp()
    {
        if (Config::get('abhayagiri.git_versioning')) {
            return static::gitRevision();
        } else {
            return (string) time();
        }
    }
}
