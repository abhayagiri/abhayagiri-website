<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('to administrate the site securely');
$I->amOnPage('/mahapanel');
$I->see('Sign in with your Google Account');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->seeCurrentUrlEquals('/mahapanel/dashboard');

$I->amOnPage('/mahapanel');
$I->seeCurrentUrlEquals('/mahapanel/dashboard');
