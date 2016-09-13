<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to ensure mahapanel is secure');

$I->amOnPage('/mahapanel');
$I->see('Google');
$I->seeCurrentUrlMatches('~^/o/oauth2/auth~');

$I->amOnPage('/mahapanel/login');
$I->see('Google');
$I->seeCurrentUrlMatches('~^/o/oauth2/auth~');

$I->amOnPage('/mahapanel/logout');
$I->see('Logged out');
$I->seeCurrentUrlEquals('/mahapanel/logout');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->seeCurrentUrlEquals('/mahapanel');

$I->amOnPage('/mahapanel');
$I->seeCurrentUrlEquals('/mahapanel');

$I->amOnPage('/mahapanel/logout');
$I->see('Logged out');
$I->seeCurrentUrlEquals('/mahapanel/logout');

$I->amOnPage('/mahapanel');
$I->see('Google');
$I->seeCurrentUrlMatches('~^/o/oauth2/auth~');
