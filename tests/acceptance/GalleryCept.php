<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the gallery works');

$I->amOnPage('/home');
$I->waitForPageToLoad();

$I->click('#btn-menu');
$I->click('#btn-gallery');
$I->waitForPageToLoad();
$I->see('Gallery');
$I->see('Reception Hall - Season 4 - Installment 8');

$I->click('Reception Hall - Season 4 - Installment 8');
$I->waitForPageToLoad();
$I->see('Reception Hall - Season 4 - Installment 8');
$I->seeElement('a.thumbnail');
