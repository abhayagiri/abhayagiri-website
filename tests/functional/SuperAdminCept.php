<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the admin works');

$user = \App\User::where('email', 'root@localhost')->firstOrFail();
Auth::login($user);

$I->amOnPage('/admin');
$I->see('Dashboard');

$I->click('Users');
$I->seeCurrentUrlEquals('/admin/users');
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
$I->see('Add user');

Auth::logout();
