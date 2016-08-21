<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the English homepage works');

$I->amOnPage('/home');
$I->see('News');
$I->see('Calendar');

$I->click('More News');
$I->waitForElementVisible('#content table');
$I->see('back to top');
$I->see('Read More');

$I->click('Directions');
$I->waitForText('Directions', 10, 'legend');
$I->see('16201 Tomki Road');
