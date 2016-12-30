<?php

$searchInputSelector = '.dataTables_filter input';

$I = new AcceptanceTester($scenario);
$I->wantTo('search Mahapanel');

$I->amOnPage('/mahapanel_bypass?email=root@localhost');
$I->waitForPageToLoad();
$I->seeCurrentUrlEquals('/mahapanel/dashboard');

$I->click('button[title~=MENU]');
$I->click('a[href="/subpages"]');
$I->waitForPageToLoad();
$I->see('Subpages');

$I->fillField($searchInputSelector, 'daily');
$I->waitForPageToLoad();
$I->waitForText('Daily Schedule');
$I->see('daily-schedule');
