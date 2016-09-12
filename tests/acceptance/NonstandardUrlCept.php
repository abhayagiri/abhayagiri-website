<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('make sure non-standard URLs still load');

$I->amOnPage('/news');
$I->waitForPageToLoad();
$I->click('Somkid\'s Photography');
$I->waitForPageToLoad();
$I->see('a professional photographer');

$I->amOnPage('/news/somkid\'s-photography');
$I->waitForPageToLoad();
$I->see('a professional photographer');
