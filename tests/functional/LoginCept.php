<?php

$I = new FunctionalTester($scenario);
$I->wantTo('see the homepage');

$I->amOnPage('/home');
$I->see('Abhayagiri');

$I->amOnPage('/contact');
$I->see('Contact Form');
