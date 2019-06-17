<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the super admin works');

$email = config('abhayagiri.auth.mahapanel_admin');
$user = \App\User::where('email', $email)->firstOrFail();
\Auth::login($user);

$I->amOnPage('/admin');
$I->see('Dashboard');

$I->click('Users');
$I->seeCurrentUrlEquals('/admin/users');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add user');
