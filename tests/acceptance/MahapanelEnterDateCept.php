<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure working inserts & updates with dates');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->waitForPageToLoad();
$I->seeCurrentUrlEquals('/mahapanel/dashboard');

$I->click('button[title~=MENU]');
$I->click('a[href="/subpages"]');
$I->waitForPageToLoad();
$I->see('Subpages');

$I->click('New Entry');
$I->waitForPageToLoad();
$I->see('Subpages Entry');

$I->fillField('form #page', 'support');
$I->fillField('form #title', 'test123');
$I->click('Submit');
$I->waitForPageToLoad();
$I->dontSee('Subpages Entry');
$I->see('test123');

$I->amOnPage('/support/test123');
$I->waitForPageToLoad();
$I->see('test123');

// TODO: This is way too convoluted...
$I->amOnPage('/mahapanel');
$I->waitForPageToLoad();
$I->seeCurrentUrlEquals('/mahapanel/dashboard');
$I->click('button[title~=MENU]');
$I->click('a[href="/subpages"]');
$I->waitForPageToLoad();
$I->see('Subpages');
$I->click('table tr:first-child button');
$I->waitForPageToLoad();
$I->see('Subpages Entry');
// Phantomjs doesn't support popups
$I->executeJS('window.confirm = function(m) { return true; };');
$I->click('Delete');
