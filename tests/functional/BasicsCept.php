<?php

$I = new FunctionalTester($scenario);
$I->wantTo('make sure the basic functionality works');

$I->amOnPage('/home');
$I->see('Abhayagiri');

$I->amOnPage('/news');
$I->see('News');

$I->amOnPage('/calendar');
$I->see('Calendar');

$I->amOnPage('/about');
$I->see('About');

$I->amOnPage('/community');
$I->see('Community');
$I->see('Ajahn Pasanno');

$I->amOnPage('/support');
$I->see('Support');
$I->see('Ethos');

$I->amOnPage('/support/dana-wish-list');
$I->see('Support');
$I->see('Dana Wish List');
$I->see('Please No Bottled Water');

$I->amOnPage('/audio');
$I->see('Audio');

$I->amOnPage('/books');
$I->see('Books');

$I->amOnPage('/reflections');
$I->see('Reflections');

$I->amOnPage('/visiting');
$I->see('Visiting');

$I->amOnPage('/contact');
$I->see('Contact Form');
