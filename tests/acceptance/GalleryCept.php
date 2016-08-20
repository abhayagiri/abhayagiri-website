<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the gallery works');
$I->amOnPage('/home');
$I->click('#btn-menu');
$I->click('#btn-gallery');

$I->waitForText('Gallery', 10, 'div.title');
$I->waitForElementVisible('#gallery');
$I->see('Reception Hall - Season 4 - Installment 8');

$I->click('Reception Hall - Season 4 - Installment 8');
$I->waitForText('Reception Hall - Season 4 - Installment 8', 10, 'legend');
$I->waitForElementVisible('#gallery');
$I->seeElement('a.thumbnail');
