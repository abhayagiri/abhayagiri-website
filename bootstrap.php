<?php

error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('display_errors', 0);

require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set(Abhayagiri\Config::get('default_timezone'));

?>
