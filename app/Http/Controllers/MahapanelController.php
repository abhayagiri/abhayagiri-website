<?php

namespace App\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\OAuth2\Client\Provider\Google as GoogleProvider;
use URL;

use App\Http\Controllers\Controller;

class MahapanelController extends Controller
{
    public function login(Request $request)
    {
        $provider = new GoogleProvider([
            'clientId'     => Config::get('abhayagiri.auth.google_client_id'),
            'clientSecret' => Config::get('abhayagiri.auth.google_client_secret'),
            'redirectUri'  => URL::to('/mahapanel/login', null, Config::get('abhayagiri.require_mahapanel_ssl')),
            'hostedDomain' => URL::to('/'),
        ]);

        $email = null;
        $error = $request->input('error');
        $code = $request->input('code');
        $oauth2state = $request->session()->get('oauth2state');

        if ($error) {

            // Got an error, probably user denied access

        } elseif (!$code) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $request->session()->put('oauth2state', $provider->getState());
            return redirect($authUrl);

        } elseif ($request->input('state') !== $oauth2state) {

            // State is invalid, possible CSRF attack in progress
            $request->session()->forget('oauth2state');
            $error = 'Invalid state';

        } else {

            // Try to get an access token (using the authorization code grant)
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $code,
                ]);

                // We got an access token, let's now get the owner details
                $ownerDetails = $provider->getResourceOwner($token);
                $email = $ownerDetails->getEmail();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        if ($email) {
            if ($id = \App\Legacy\DB::getDB()->login($email)) {
                $request->session()->put('mahaguild_id', $id);
                return redirect('/mahapanel');
            } else {
                $error = 'No email in our system for ' . $email;
            }
        }

        return view('mahapanel/login', ['error' => $error]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('mahaguild_id');
        return view('mahapanel/login', ['error' => 'Logged out']);
    }
}
