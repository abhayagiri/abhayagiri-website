#!/usr/bin/env php
<?php

require_once __DIR__.'/../bootstrap.php';

$maxage = 60 * 60 * 24; // 1 day
$tmpdir = __DIR__ . '/../tmp';
$local_db_path = "$tmpdir/db-public-latest.sql";
$public_db_url = 'https://dev.abhayagiri.org/export/db-public-latest.sql';

if (!file_exists($local_db_path) ||
    time() - filemtime($local_db_path) > $maxage) {
    system("curl -o '$local_db_path' '$public_db_url'");
}

$host = Config::get('database.connections.mysql.host');
$database = Config::get('database.connections.mysql.database');
$username = Config::get('database.connections.mysql.username');
$password = Config::get('database.connections.mysql.password');

$cmd = "echo 'DROP DATABASE $database; CREATE DATABASE $database;' | " .
       "mysql -u $username -h $host -p'$password'";

system($cmd);

$cmd = "cat '$local_db_path' | " .
       "mysql -u $username -h $host -p'$password' $database";

system($cmd);
