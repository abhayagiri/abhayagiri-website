<?

session_start();
if (isset($_SESSION['authorized'])) {
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $user = $_SESSION['user'];
    $avatar = $_SESSION['avatar'];
    $banner = $_SESSION['banner'];
    $access = $_SESSION['access'];
} else {
    header('Location:https://mahapanel.abhayagiri.org/openid/login.php');
}
?>