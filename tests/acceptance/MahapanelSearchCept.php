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

$I->fillField($searchInputSelector, 'kathina');
$I->waitForPageToLoad();
$I->waitForText('Kathina Wish List');
$I->see('kathina-wish-list');
