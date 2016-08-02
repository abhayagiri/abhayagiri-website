<?php

require_once __DIR__ . '/mahapanel-bootstrap.php';

$provider = new League\OAuth2\Client\Provider\Google([
    'clientId'     => Abhayagiri\Config::get('googleAuthClientId'),
    'clientSecret' => Abhayagiri\Config::get('googleAuthClientSecret'),
    'redirectUri'  => Abhayagiri\getMahapanelRoot() . '/login.php',
    'hostedDomain' => Abhayagiri\getWebRoot(),
]);

if (!empty($_GET['error'])) {

    // Got an error, probably user denied access
    exit('Got error: ' . $_GET['error']);

} elseif (empty($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    Abhayagiri\redirect($authUrl);

} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    // State is invalid, possible CSRF attack in progress
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

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
        print 'Error: ' . $e->getMessage();
    }

    if ($email) {
        if ($session = Abhayagiri\DB::getDB()->login($email)) {
            $_SESSION = array_merge($_SESSION, $session);
            $_SESSION['authorized'] = true;
            Abhayagiri\redirect(Abhayagiri\getMahapanelRoot());
        } else {
            print 'No email in our system' . $email;
        }
    }
}
