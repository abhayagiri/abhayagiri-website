<?php

require_once __DIR__ . '/mahapanel-bootstrap.php';

$provider = new League\OAuth2\Client\Provider\Google([
    'clientId'     => Config::get('abhayagiri.auth.google_client_id'),
    'clientSecret' => Config::get('abhayagiri.auth.google_client_secret'),
    'redirectUri'  => URL::to('/mahapanel/login.php', null, Config::get('abhayagiri.require_mahapanel_ssl')),
    'hostedDomain' => URL::to('/'),
]);

$error = null;

if (!empty($_GET['error'])) {

    // Got an error, probably user denied access
    $error = $_GET['error'];

} elseif (empty($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl(['approval_prompt' => 'force']);
    $_SESSION['oauth2state'] = $provider->getState();
    Abhayagiri\redirect($authUrl);

} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    // State is invalid, possible CSRF attack in progress
    unset($_SESSION['oauth2state']);
    $error = 'Invalid state';

} else {

    // Try to get an access token (using the authorization code grant)
    $email = null;

    try {
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We got an access token, let's now get the owner details
        $ownerDetails = $provider->getResourceOwner($token);
        $email = $ownerDetails->getEmail();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    if ($email) {
        if ($session = Abhayagiri\DB::getDB()->login($email)) {
            $_SESSION = array_merge($_SESSION, $session);
            $_SESSION['authorized'] = true;
            Abhayagiri\redirect('/mahapanel/');
        } else {
            $error = 'No email in our system for ' . $email;
        }
    }
}

if (!$error) {
    $error = 'Unknown error';
}

session_destroy();

?>

Error: <?php echo $error ?>

<a href="/mahapanel/login.php">Try again.</a>
