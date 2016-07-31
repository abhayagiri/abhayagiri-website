<?php

session_start();
$name = $_POST['name'];
$avatar = $_POST['avatar'];
$banner = $_POST['banner'];

if (isset($name)) {
    $_SESSION['name'] = $_POST['name'];
}

if (isset($avatar) && $avatar != "") {
    $_SESSION['avatar'] = $_POST['avatar'];
}

if (isset($banner) && $banner != "") {
    $_SESSION['banner'] = $_POST['banner'];
}
?>
