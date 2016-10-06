<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the gallery works');

$I->amOnPage('/home');
$I->waitForPageToLoad();

$I->click('#btn-menu');
$I->click('#btn-gallery');
$I->waitForPageToLoad();
$I->see('Gallery');
$I->see('Songkran 2016');

$I->click('Songkran 2016');
$I->waitForPageToLoad();
$I->see('Songkran 2016');
$I->seeElement('a.thumbnail');
