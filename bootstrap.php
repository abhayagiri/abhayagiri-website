<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = Abhayagiri\Config::getConfig();

date_default_timezone_set($config['default_timezone']);

?>
