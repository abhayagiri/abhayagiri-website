#!/usr/bin/env php
<?php

$maxage = 60 * 60 * 24; // 1 day
$tmpdir = __DIR__ . '/../tmp';
$local_db_path = "$tmpdir/db-public-latest.sql";
$public_db_url = 'https://dev.abhayagiri.org/export/db-public-latest.sql';

if (!file_exists($local_db_path) ||
    time() - filemtime($local_db_path) > $maxage) {
    system("curl -o '$local_db_path' '$public_db_url'");
}

require_once __DIR__ . '/../config/config.php';

preg_match('/:dbname=(.+);host=(.+);/', $config['db']['dsn'], $matches);

$db = $matches[1];
$hostname = $matches[2];
$username = $config['db']['username'];
$password = $config['db']['password'];

$cmd = "cat '$local_db_path' | " .
       "mysql -u $username -h $hostname -p'$password' $db";

system($cmd);

?>
