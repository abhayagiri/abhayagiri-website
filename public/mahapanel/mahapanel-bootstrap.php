<?php

require_once __DIR__ . '/../www-bootstrap.php';

if (Config::get('abhayagiri.require_mahapanel_ssl') && !Abhayagiri\isSSL()) {
    Abhayagiri\redirect($_SERVER['REQUEST_URI'], true);
}

?>
