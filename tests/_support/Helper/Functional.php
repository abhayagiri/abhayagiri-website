<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    public function amASuperAdmin() {
        $email = config('abhayagiri.auth.mahapanel_admin');
        $user = \App\User::where('email', $email)->firstOrFail();
        backpack_auth()->login($user);
    }
}
