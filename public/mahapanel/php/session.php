<?php

require_once __DIR__ . '/../mahapanel-bootstrap.php';

if (isset($_SESSION['authorized'])) {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $user = $_SESSION['user'];
    $avatar = $_SESSION['avatar'];
    $banner = $_SESSION['banner'];
    $access = $_SESSION['access'];
} else {
    Abhayagiri\redirect('/mahapanel/login.php');
}

?>