#!/usr/bin/env php
<?php

require_once __DIR__ . '/../config/config.php';

preg_match('/:dbname=(.+);host=(.+);/', $config['db']['dsn'], $matches);

$db = $matches[1];
$hostname = $matches[2];
$username = $config['db']['username'];
$password = $config['db']['password'];

$cmd = "curl https://dev.abhayagiri.org/export/db-public-latest.sql | " .
       "mysql -u $username -h $hostname -p'$password' $db";

system($cmd);
?>
