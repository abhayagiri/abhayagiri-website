<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the gallery works');

$I->amOnPage('/home');
$I->waitForPageToLoad();

$I->click('#btn-menu');
$I->click('#btn-gallery');
$I->waitForPageToLoad();
$I->see('Gallery');
// TODO This is pretty awful but it will do for now...
$I->see('Bhikkhu Ordination');

$I->click('Bhikkhu Ordination');
$I->waitForPageToLoad();
$I->see('Bhikkhu Ordination');
$I->seeElement('a.thumbnail');
