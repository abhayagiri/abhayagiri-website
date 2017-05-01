<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that the audio page works');

$I->amOnPage('/home');
$I->waitForPageToLoad();

$I->click('#btn-menu');
$I->click('#btn-audio');
$I->waitForPageToLoad();
$I->see('Audio');
$I->see('Play');
