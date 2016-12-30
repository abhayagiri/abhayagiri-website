<?php

$searchInputSelector = '.input-append input';

$I = new AcceptanceTester($scenario);
$I->wantTo('make sure non-standard URLs still load');

$I->amOnPage('/news');
$I->waitForPageToLoad();

$I->fillField($searchInputSelector, 'somkid');
$I->waitForPageToLoad();
$I->waitForText('Somkid');
$I->see('a professional photographer');

$I->click('Somkid\'s Photography');
$I->waitForPageToLoad();
$I->see('a professional photographer');

$I->amOnPage('/news/somkid\'s-photography');
$I->waitForPageToLoad();
$I->see('a professional photographer');
