<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the English homepage works');

$I->amOnPage('/home');
$I->waitForPageToLoad();
$I->see('News');
$I->see('Calendar');

$I->click('More News');
$I->waitForPageToLoad();
$I->see('back to top');
$I->see('Read More');

$I->click('Directions');
$I->waitForPageToLoad();
$I->see('Directions', 'legend');
$I->see('16201 Tomki Road');
