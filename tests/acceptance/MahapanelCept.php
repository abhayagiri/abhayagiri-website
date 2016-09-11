<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('to administrate the site securely');

$I->amOnPage('/mahapanel');
if (env('AUTH_GOOGLE_CLIENT_ID')) {
    $scenario->skip('waiting for new markup');
}

$I->see('Sign in with your Google Account');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->wait(1);
$I->seeCurrentUrlEquals('/mahapanel/dashboard');

$I->amOnPage('/mahapanel');
$I->wait(1);
$I->seeCurrentUrlEquals('/mahapanel/dashboard');
