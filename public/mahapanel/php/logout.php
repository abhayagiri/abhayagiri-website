<?php

require_once __DIR__ . '/../mahapanel-bootstrap.php';

session_start();
session_destroy();

Abhayagiri\redirect(Abhayagiri\getMahapanelRoot());

?>
