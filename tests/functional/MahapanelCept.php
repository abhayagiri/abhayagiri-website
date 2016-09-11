<?php

$I = new FunctionalTester($scenario);
$I->wantTo('to ensure mahapanel is secure');
$I->amOnPage('/mahapanel');
$I->see('Google');
$I->seeCurrentUrlMatches('~^/o/oauth2/auth~');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->seeCurrentUrlEquals('/mahapanel');

$I->amOnPage('/mahapanel');
$I->seeCurrentUrlEquals('/mahapanel');
