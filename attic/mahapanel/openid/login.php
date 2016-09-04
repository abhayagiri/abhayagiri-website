<?php

require_once __DIR__ . '/../mahapanel-bootstrap.php';

if($_REQUEST['login']){
    session_start();
    require '../php/db.php';

    $domain = 'abhayagiri.org';
    $sila = 'https://mahapanel.abhayagiri.org/openid/hackercrap.html';
    $redirect = 'https://mahapanel.abhayagiri.org';
    $email = $_REQUEST['email'];
    $fname = $_REQUEST['fname'];;
    $lname = $_REQUEST['lname'];;
    $name = $fname . ' ' . $lname;
    
    if ($db->_login($name, $email)) {
        header('Location: ' . $redirect);
    }else{
        header('Location: ' . $sila);
    }
}
?>
<html lang="en">
  <head>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="370991231759.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
      function onSignIn(googleUser) {
        
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        var name = profile.getName().split(" ");
        var fname = name[0];
        var lname = name[1];
        var email = profile.getEmail();

        window.location.href = "<?php echo App\Util::redirectUrl('/openid/login.php') ?>?login=true&email=" + email + "&fname=" + fname + "&lname=" + lname;
      };
    </script>
  </body>
</html>