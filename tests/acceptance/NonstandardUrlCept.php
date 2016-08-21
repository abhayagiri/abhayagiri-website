<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('make sure non-standard URLs still load');

$I->amOnPage('/news');
$I->click('Somkid\'s Photography');

$I->waitForElementVisible('#content');
$I->see('a professional photographer');

$I->amOnPage('/news/somkid\'s-photography');
$I->see('a professional photographer');
