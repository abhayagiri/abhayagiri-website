<?php

require_once __DIR__ . '/../www-bootstrap.php';

if (Abhayagiri\Config::get('requireHahapanelSSL') && !Abhayagiri\isSSL()) {
    Abhayagiri\redirect($_SERVER['REQUEST_URI'], true);
}

?>
