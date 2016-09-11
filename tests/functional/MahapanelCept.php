<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to ensure mahapanel is secure');
$I->amOnPage('/mahapanel');

if (env('AUTH_GOOGLE_CLIENT_ID')) {
    $I->see('Google');
} else {
    $I->see('Missing required parameter: client_id');
}
$I->seeCurrentUrlMatches('~^/o/oauth2/auth~');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->seeCurrentUrlEquals('/mahapanel');

$I->amOnPage('/mahapanel');
$I->seeCurrentUrlEquals('/mahapanel');
